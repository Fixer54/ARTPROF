var substringMatcher = function(strs) {
  return function findMatches(q, cb) {
    var matches, substringRegex;

    // an array that will be populated with substring matches
    matches = [];

    // regex used to determine if a string contains the substring `q`
    substrRegex = new RegExp(q, 'i');

    // iterate through the pool of strings and for any string that
    // contains the substring `q`, add it to the `matches` array
    jQuery.each(strs, function(i, str) {
      if (substrRegex.test(str)) {
        matches.push(str);
      }
    });

    cb(matches);
  };
};

var states = ['Critiques', 'Portfolio Critiques', 'Courses', 'Art Dares', 'Professional Development',
  'FAQ', 'Q&Art', 'questions', 'TA Bios', 'Clara Lieu', 'Charcoal',
  'Pencil', 'Watercolor', 'Supplies', 'Art Supplies', 'Painting', 'Drawing', 'Sketch',
  'Beginner Articles', 'Articles', 'Advanced Articles', 'Videos', 'Intermediate Articles',
  'Transcripts', 'Assignments', 'How do I draw noses?', 'Win a portfolio critique', 'Mission', 'Contact Us'
];

jQuery('#the-basics .typeahead').typeahead({
  hint: true,
  highlight: true,
  minLength: 1
},
{
  name: 'states',
  source: substringMatcher(states)
});