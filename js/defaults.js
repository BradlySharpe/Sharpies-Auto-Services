var SAS = {
  baseURL: '/Sharpies-Auto-Services/',
  params: [],
  getParams: function() {
    var hash = window.location.hash.substr(1);
    var pairs = hash.split("&");
    for (var i = 0; i < pairs.length; i++) {
      var pair = pairs[i].split('=');
      if (2 == pair.length)
        SAS.params[pair[0]] = pair[1];
    }
  }
};
SAS.getParams();
console.log("Params", SAS);

var sort_by;
(function() {
  // utility functions
  // http://stackoverflow.com/questions/6913512/how-to-sort-an-array-of-objects-by-multiple-fields
  var default_cmp = function(a, b) {
    if (a == b) return 0;
    return a < b ? -1 : 1;
  },
  getCmpFunc = function(primer, reverse) {
    var dfc = default_cmp, // closer in scope
      cmp = default_cmp;
    if (primer) {
      cmp = function(a, b) {
        return dfc(primer(a), primer(b));
      };
    }
    if (reverse) {
      return function(a, b) {
        return -1 * cmp(a, b);
      };
    }
    return cmp;
  };

  // actual implementation
  sort_by = function() {
    var fields = [],
      n_fields = arguments.length,
      field, name, reverse, cmp;

    // preprocess sorting options
    for (var i = 0; i < n_fields; i++) {
      field = arguments[i];
      if (typeof field === 'string') {
        name = field;
        cmp = default_cmp;
      }
      else {
        name = field.name;
        cmp = getCmpFunc(field.primer, field.reverse);
      }
      fields.push({
        name: name,
        cmp: cmp
      });
    }

    // final comparison function
    return function(A, B) {
      var a, b, name, result;
      for (var i = 0; i < n_fields; i++) {
        result = 0;
        field = fields[i];
        name = field.name;

        result = field.cmp(A[name], B[name]);
        if (result !== 0) break;
      }
      return result;
    };
  };
}());

Number.prototype.formatMoney = function(c, d, t){
  //http://stackoverflow.com/questions/149055/how-can-i-format-numbers-as-money-in-javascript
  var n = this,
      c = isNaN(c = Math.abs(c)) ? 2 : c,
      d = d == undefined ? "." : d,
      t = t == undefined ? "," : t,
      s = n < 0 ? "-" : "",
      i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
      j = (j = i.length) > 3 ? j % 3 : 0;
  return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};
