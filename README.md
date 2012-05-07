# Zenbu Tag Formatting
Formats Solspace's Tag Fieldtypes in Zenbu.

This extension for the [Zenbu](http://devot-ee.com/add-ons/zenbu/) add-on modifies how tags from the [Solspace Tag](http://www.solspace.com/docs/addon/c/Tag) module are displayed in Zenbu columns.

This add-on is also a teaching aid/example for developers who wish to modify the display of Zenbu column data using the **zenbu_modify_field_cell_data** hook in Zenbu.

## Installation

1. Download the add-on, and rename the folder to **zenbu_tag_formatting**
2. Place the **zenbu_tag_formatting** folder into your ExpressionEngine's third_party folder (typically found at system/expressionengine/third_party)
3. In the Control Panel, under Add-ons => Extensions, click on the "Enable" link next to **Zenbu Solspace Tag formatting extension**
4. Click on the "settings" link in the Control Panel's Extensions section to set up the add-on

## Options

### Separate tags by

Separates tags by the following characters once displayed in Zenbu:

- Comma (eg. Apples, Oranges, Pineapples)
- Linebreak (eg. Apples&lt;br /&gt;Oranges&lt;br /&gt;Pineapples&lt;br /&gt;)