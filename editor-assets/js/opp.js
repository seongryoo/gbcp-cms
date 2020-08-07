(function(wp) {
  // React aliases
  const el = wp.element.createElement;
  const registerBlock = wp.blocks.registerBlockType;
  // database api
  const withSelect = wp.data.withSelect;
  const withDispatch = wp.data.withDispatch;
  const compose = wp.compose.compose;
  const withState = wp.compose.withState;
  // core api helper methods
  const getAttr = wp.data.select('core/editor').getEditedPostAttribute;
  // Components
  const CheckboxControl = wp.components.CheckboxControl;
  const TextControl = wp.components.TextControl;
  const RichText = wp.blockEditor.RichText;
  const RadioControl = wp.components.RadioControl;
  // Calendar help
  const date = wp.date.date;
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
    // Helper method that generates appia field wrapper
    const elWrap = function(element, args, value) {
      let generatedElement;
      if (arguments.length == 3) {
        generatedElement = el(element, args, value);
      } else if (arguments.length == 2) {
        generatedElement = el(element, args);
      } else if (arguments.length == 1) {
        generatedElement = el(element);
      }
      return el(
          'div',
          {
            className: 'gbcp-field-block',
          },
          generatedElement
      );
    };
    const generateTextField = function(attribute, label) {
      const elText = elWrap(
          TextControl,
          {
            value: props.attributes[attribute],
            onChange: function(value) {
              props.setAttributes({
                [attribute]: value,
                className: 'gbcp-input__text',
              });
            },
            placeholder: 'Start typing...',
            label: label,
          }
      );
      return elText;
    };
    const generateRichText = function(attribute, label) {
      const elText = el(
          RichText,
          {
            value: props.attributes[attribute],
            onChange: function(value) {
              props.setAttributes({
                [attribute]: value,
              });
            },
            placeholder: 'Start typing...',
            id: 'richtext-' + attribute,
            className: 'gbcp-input__rich-text',
            multiline: true,
          }
      );
      const elHint = el(
          'p',
          {
            className: 'hint',
          },
          'Hint: you can insert links or use bold/italic text'
      );
      const elLabel = el(
          'label',
          {
            for: 'richtext-' + attribute,
          },
          label
      );
      return elWrap(
          'div',
          {},
          [elLabel, elHint, elText]
      );
    };
    // Date field
    const updateDate = function(newDate) {
      props.setAttributes({expr: newDate});
    };
    const getChosenDate = function() {
      return props.attributes.expr == '' ? null : props.attributes.expr;
    };
    const calendarArgs = {
      currentDate: getChosenDate(),
      onChange: updateDate,
      id: 'date-select',
    };
    const calendarElement = el(
        wp.components.DatePicker,
        calendarArgs
    );
    let chosenDate = '';
    if (props.attributes.will_expire && props.attributes.expr != '') {
      const dateString = date('l, F j, Y', props.attributes.expr);
      chosenDate = '(' + dateString + ')';
    }
    // Radio element
    const optionValues = [
      {label: 'Never', value: 'never'},
      {label: 'After a specific date ' + chosenDate, value: 'after'},
    ];
    const willItExpire = function() {
      return props.attributes.will_expire ? 'after' : 'never';
    };
    const radio = el(
        RadioControl,
        {
          label: 'Opportunity expires',
          selected: willItExpire(),
          options: optionValues,
          className: 'gbcp-radio',
          onChange: function(value) {
            props.setAttributes({
              will_expire: value == 'after',
            });
          },
        }
    );
    const expirationControl = elWrap(
        'div',
        {
          className: 'gbcp-expiration',
        },
        props.attributes.will_expire ?
          [radio, calendarElement] : [radio]
    );
    const generateTagGroup = function(allTags, slug) {
      const taxonomyName = taxons[slug].name;
      const elementArray = [];
      for (const tag of allTags) {
        const id = tag.id;
        const name = tag.name;
        const description = tag.description;
        const checkbox = el(
            CheckboxControl,
            {
              'className': 'gbcp-checkbox',
              'data-id': id,
              'checked': props[slug].indexOf(id) != -1,
              'label': name,
              'help': description,
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
      return elWrap(
          'div',
          {

          },
          [label, group]
      );
    }; // End generateCheckboxes()
    const timeTags = generateTagGroup(props.allTimes, 'taxon_time');
    const typeTags = generateTagGroup(props.allTypes, 'taxon_type');
    const locTags = generateTagGroup(props.allLocs, 'taxon_loc');
    const desc = generateRichText('desc', 'Opportunity description');
    const levelDesc = generateTextField('level',
        'Short description of time commitment '
      + '(e.g. "2-day event" or "1 semester")');
    const locDesc = generateTextField('loc',
        'Short description of location '
      + '(e.g. "Room 315 in CULC" or "Savannah, GA")');
    return el(
        'div',
        {
          className: 'gbcp-blocks',
        },
        [
          desc,
          typeTags,
          locTags,
          locDesc,
          timeTags,
          levelDesc,
          expirationControl,
        ]
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
