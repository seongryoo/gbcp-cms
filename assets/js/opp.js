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
    const getTypeData = function() {
      return select('core/editor').getEditedPostAttribute('taxon_type');
    };
    return {
      times: timeTerms,
      types: typeTerms,
      locs: locTerms,
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
    if (!props.times || !props.types || !props.locs) {
      return 'Fetching tags...';
    }
    if (!props.taxon_time || !props.taxon_type || !props.taxon_loc) {
      return 'Fetching post data...';
    }
    console.log(props.times);
    console.log(props.types);
    console.log(props.locs);
    const generateTags = function(allTags, elementArray, slug) {
      const chosenTags = props[slug];
      const isCheckedIn = function(id, array) {
        for (const tagId of array) {
          if (id == tagId) {
            console.log('uip');
            return true;
          }
        }
        return false;
      }
      const isChecked = function(id) {
        return isCheckedIn(chosenTags);
      };
      for (const tag of allTags) {
        const id = tag.id;
        const name = tag.name;
        const checkbox = el(
            CheckboxControl,
            {
              'className': 'appia-tag',
              'data-id': id,
              'checked': isCheckedIn(id, props[slug]),
              'label': name,
              'onChange': function() {
                let fetchSlugs = props[slug].slice(0);
                let theArray = [];
                if (fetchSlugs.indexOf(id) == -1) {
                  fetchSlugs.push(id)
                } else {
                  const removeIndex = fetchSlugs.indexOf(id);
                  console.log('Index of ' + id + ' is at ' + removeIndex)
                  fetchSlugs.splice(removeIndex, 1);
                  console.log(fetchSlugs)
                }
                console.log(fetchSlugs)
                props.setTerm(slug, fetchSlugs);
              },
            }
        );
        elementArray.push(checkbox);
      }
    }
    const elTimeTags = [];
    generateTags(props.times, elTimeTags, 'taxon_time');
    const elTypeTags = [];
    generateTags(props.types, elTypeTags, 'taxon_type');
    const elLocTags = [];
    generateTags(props.locs, elLocTags, 'taxon_loc');
    return el(
        'div',
        {},
        [elTimeTags, elTypeTags, elLocTags]
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
