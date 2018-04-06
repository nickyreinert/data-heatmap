# data-heatmap
Data-heatmp is a plugin for wordpress to display a heatmap as an HTML-table. It provides a shortcode ([data-heatmap]) that will render content from a source data table with three columns (x-Axis, y-Axis and values) into an HTML-table. Based on the values, the cells of the HTML-table will be colored in different shades. 

The source data table has to be uploaded to the page or post inside a custom field. The name of the custom field has to be `data-heatmap`. The first row should contain the header and define, what dimension each column contains:

* x - values of the xAxis, will be recocknized as the columns / table-headers of the HTML-table
* y - values of the yAxis, will be reckognized as the first column of every row of the HTML-table
* v - values for the cell content, will be put into the cell of every row

The shortcode supports a set of parameters. All parameters are optional. If you do not provide them, the default values (in brackets) will be taken.

* source-id (data-heatmap) - of course you can change the name of the custom field, if you provide a value for this parameter
* basecolor (#ff0000) - the background-color of every single cell, based on the containing value, this color will be modified with a higher transparancy
* fontSize (8) - used for the values of every single cell
* hide-values (no) - change this to yes, if you do not want to display the values inside every cell, if you hide the values, they will be displayed when hovering over each cell
* hide-xaxis / hide-yaxis (no) - you can change this parameter to yes, to hide the x- or y-axis
* transpose (no) - change this to yes if you want to switch x and y axis
* sort-yaxis (no) - if you want to sort the y-axis based on the sum of the rows, set this parameter to yes

# installation

Just download the complete package and put it into a separate folder into your plugins-folder of Wordpress (wp-content/plugins/) and activate this plugin in the backend. 

# example usage
An example can be found here (in German only)

https://www.nickyreinert.de/eine-html-tabelle-als-heatmap-in-wordpress-darstellen/
