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
        .getCurrentPostAttribute('taxon_type');
    const timeData = select('core/editor')
        .getCurrentPostAttribute('taxon_time');
    const locData = select('core/editor')
        .getCurrentPostAttribute('taxon_loc');
    const idData = select('core/editor')
        .getCurrentPostId();
    return {
      times: timeTerms,
      types: typeTerms,
      locs: locTerms,
      chosenTypes: typeData,
      chosenTimes: timeData,
      chosenLocs: locData,
      postId: idData,
    };
  });
  const oppEdit = compose(fetchTerms)(function(props) {
    if (!props.times || !props.types || !props.locs) {
      return 'Fetching tags...';
    }
    if (!props.chosenTimes || !props.chosenTypes || !props.chosenLocs) {
      return 'Fetching post data...';
    }
    console.log(props.times);
    console.log(props.types);
    console.log(props.locs);
    const generateTags = function(data, arr, chosenTags) {
      const isChecked = function(id) {
        for (const tagId of chosenTags) {
          if (id == tagId) {
            return true;
          }
        }
        return false;
      };
      for (const tag of data) {
        const id = tag.id;
        const name = tag.name;
        const checkbox = el(
            CheckboxControl,
            {
              'className': 'appia-tag',
              'data-id': id,
              'checked': isChecked(id),
              'label': name,
              'onChange': function(value) {
                console.log(value)
              },
            }
        );
        arr.push(checkbox);
      }
    }
    const timeTags = [];
    generateTags(props.times, timeTags, props.chosenTimes);
    const typeTags = [];
    generateTags(props.types, typeTags, props.chosenTypes);
    const locTags = [];
    generateTags(props.locs, locTags, props.chosenLocs);
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
