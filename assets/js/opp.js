(function(wp) {
  // React aliases
  const el = wp.element.createElement;
  const registerBlock = wp.blocks.registerBlockType;
  // database api
  const withSelect = wp.data.withSelect;
  const withDispatch = wp.data.withDispatch;
  const compose = wp.compose.compose;
  // Components
  const CheckboxControl = wp.components.CheckboxControl;
  const fetchTerms = withSelect(function(select) {
    const queryArgs = {
      per_page: -1,
    };
    const locTerms = select('core').getEntityRecords(
        'taxonomy',
        'taxon_loc',
        queryArgs
    );
    const timeTerms = select('core').getEntityRecords(
        'taxonomy',
        'taxon_time',
        queryArgs
    );
    const typeTerms = select('core').getEntityRecords(
        'taxonomy',
        'taxon_type',
        queryArgs
    );
    const typeData = select('core/editor')
        .getEditedPostAttribute('taxon_type');
    const timeData = select('core/editor')
        .getEditedPostAttribute('taxon_time');
    const locData = select('core/editor')
        .getEditedPostAttribute('taxon_loc');
    const idData = select('core/editor')
        .getCurrentPostId();
    return {
      allTimes: timeTerms,
      allTypes: typeTerms,
      allLocs: locTerms,
      taxon_type: typeData,
      taxon_time: timeData,
      taxon_loc: locData,
      postId: idData,
    };
  });
  const editTerms = withDispatch(function(dispatch, props) {
    return {
      setTerm: function(term, idArray) {
        dispatch('core').editEntityRecord(
            'postType',
            'post_opp',
            props.postId,
            {
              [term]: idArray,
            }
        );
      },
    };
  });
  const oppEdit = compose(fetchTerms, editTerms)(function(props) {
    if (!props.allTimes || !props.allTypes || !props.allLocs) {
      return 'Fetching tags...';
    }
    if (!props.taxon_time || !props.taxon_type || !props.taxon_loc) {
      return 'Fetching post data...';
    }
    const generateCheckboxes = function(allTags, elementArray, slug) {
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
    }; // End generateCheckboxes()
    const timeCheckboxArray = [];
    generateCheckboxes(props.allTimes, timeCheckboxArray, 'taxon_time');
    const typeCheckboxArray = [];
    generateCheckboxes(props.allTypes, typeCheckboxArray, 'taxon_type');
    const locCheckboxArray = [];
    generateCheckboxes(props.allLocs, locCheckboxArray, 'taxon_loc');
    return el(
        'div',
        {},
        [timeCheckboxArray, typeCheckboxArray, locCheckboxArray]
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
