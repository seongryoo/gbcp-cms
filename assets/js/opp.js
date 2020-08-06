(function(wp) {
  // React aliases
  const el = wp.element.createElement;
  const registerBlock = wp.blocks.registerBlockType;
  // database api
  const withSelect = wp.data.withSelect;
  const withDispatch = wp.data.withDispatch;
  const compose = wp.compose.compose;
  // core api helper methods
  const getAttr = wp.data.select('core/editor').getEditedPostAttribute;
  // Components
  const CheckboxControl = wp.components.CheckboxControl;
  // Get taxonomy data
  let taxons;
  wp.apiFetch({path: '/wp/v2/taxonomies'}).then((taxonomies) => {
    taxons = taxonomies;
  });
  let allLocs;
  wp.apiFetch({path: '/wp/v2/taxon_loc'}).then((terms) => {
    allLocs = terms;
  });
  let allTimes;
  wp.apiFetch({path: '/wp/v2/taxon_time'}).then((terms) => {
    allTimes = terms;
  });
  let allTypes;
  wp.apiFetch({path: '/wp/v2/taxon_type'}).then((terms) => {
    allTypes = terms;
  });
  const fetchTerms = withSelect(function(select) {
    const typeData = getAttr('taxon_type');
    const timeData = getAttr('taxon_time');
    const locData = getAttr('taxon_loc');
    const idData = select('core/editor').getCurrentPostId();

    return {
      allTimes: allTimes,
      allTypes: allTypes,
      allLocs: allLocs,
      taxon_type: typeData,
      taxon_time: timeData,
      taxon_loc: locData,
      postId: idData,
    };
  });
  const editTerms = withDispatch(function(dispatch, props) {
    return {
      setTerm: function(term, idArray) {
        const edits = {
          [term]: idArray,
        };
        dispatch('core').editEntityRecord(
            'postType',
            'post_opp',
            props.postId,
            edits
        );
      },
    };
  });
  const oppEdit = compose(fetchTerms, editTerms)(function(props) {
    if (!taxons || !props.allTimes || !props.allTypes || !props.allLocs) {
      return 'Fetching tags...';
    }
    if (!props.taxon_time || !props.taxon_type || !props.taxon_loc) {
      return 'Fetching post data...';
    }
    const generateTagGroup = function(allTags, slug) {
      const taxonomyName = taxons[slug].name;
      const elementArray = [];
      for (const tag of allTags) {
        const id = tag.id;
        const name = tag.name;
        const checkbox = el(
            CheckboxControl,
            {
              'className': 'appia-tag',
              'data-id': id,
              'checked': props[slug].indexOf(id) != -1,
              'label': name,
              'onChange': function() {
                const fetchSlugs = props[slug].slice(0);
                if (fetchSlugs.indexOf(id) == -1) {
                  fetchSlugs.push(id);
                } else {
                  const removeIndex = fetchSlugs.indexOf(id);
                  fetchSlugs.splice(removeIndex, 1);
                }
                props.setTerm(slug, fetchSlugs);
              },
            }
        );
        elementArray.push(checkbox);
      } // End for iteration of allTags
      const group = el(
          'div',
          {
            id: 'field-tags-' + slug,
          },
          elementArray
      );
      const label = el(
          'label',
          {
            for: 'field-tags-' + slug,
          },
          taxonomyName
      );
      return el(
          'div',
          {

          },
          [label, group]
      );
    }; // End generateCheckboxes()
    const timeTags = generateTagGroup(props.allTimes, 'taxon_time');
    const typeTags = generateTagGroup(props.allTypes, 'taxon_type');
    const locTags = generateTagGroup(props.allLocs, 'taxon_loc');
    return el(
        'div',
        {},
        [timeTags, typeTags, locTags]
    );
  });
  const oppArgs = {
    title: 'Opportunity Data',
    category: 'gbcp-opp-blocks',
    icon: 'welcome-learn-more',
    edit: oppEdit,
    save: function() {
      return null;
    },
  }; /* End oppArgs */
  registerBlock('gbcp/opp-data', oppArgs);
})(window.wp);
