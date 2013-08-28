/**
 * @summary     Percent
 * @description	Sorting plug-in for percentages 
 * @version     1.0.0
 * @file        plugin.dataTableExt.js
 * @author      Nicolas Van Labeke
 *
 */


jQuery.fn.dataTableExt.oSort['percent-asc'] = function(a, b)
{
    var x = a == "-" ? 0 : a.replace(/\./g, "");
    var y = b == "-" ? 0 : b.replace(/\./g, "");
    x = x.replace(/,/, ".");
    y = y.replace(/,/, ".");
    x = x.replace(/%/, "");
    y = y.replace(/%/, "");
 
    x = parseFloat(x);
    y = parseFloat(y);
    return ((x < y) ? -1 : ((x > y) ? 1 : 0));
};
 
jQuery.fn.dataTableExt.oSort['percent-desc'] = function(a, b)
{
    var x = a == "-" ? 0 : a.replace(/\./g, "");
    var y = b == "-" ? 0 : b.replace(/\./g, "");
    x = x.replace(/,/, ".");
    y = y.replace(/,/, ".");
    x = x.replace(/%/, "");
    y = y.replace(/%/, "");
 
    x = parseFloat(x);
    y = parseFloat(y);
    return ((x < y) ? 1 : ((x > y) ? -1 : 0));
};
jQuery.fn.dataTableExt.aTypes.unshift(
   function(sData)
   {
       var sValidChars = "0123456789.-,";
       var Char;
 
       /* Check the numeric part */
       for (i = 0; i < sData.length - 1; i++)
       {
           Char = sData.charAt(i);
           if (sValidChars.indexOf(Char) == -1)
           {
               return null;
           }
       }
 
       /* Check sufix % */
       if (sData.charAt(sData.length - 1) == "%")
       {
           return 'percent';
       }
       return null;
   }
);