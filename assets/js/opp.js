(function(wp) {
  // React aliases
  const el = wp.element.createElement;
  const registerBlock = wp.blocks.registerBlockType;
  // database api
  const withSelect = wp.data.withSelect;
  const withDispatch = wp.data.withDispatch;
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
    return {
      times: timeTerms,
      types: typeTerms,
      locs: locTerms,
    };
  });
  const oppEdit = fetchTerms(function(props) {
    if (!props.times || !props.types || !props.locs) {
      return 'Fetching tags...';
    }
    console.log(props.times);
    console.log(props.types);
    console.log(props.locs);
    return el(
        'div',
        {},
        'What?'
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
