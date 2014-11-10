Required-Table-Values
=====================

Simple plugin to quickly add required database settings in a project, a API key for example.

Usage
=====

Add the required-table-values folder to your root directory. Create your one json file for each of your tables with your table name as the file name, e.g settings.json. In this files, you have two objects, `rows` and `settings`. These two objects do as you'd expect, `rows` is where you add your table rows and `settings` is where you set your settings.

Version 1.0 Usage
=================

The File
--------
Inside of the `required-table-values` folder, create one json file for each of your tables. The naming convention is simple, `table_name.json`.

Rows
----
Rows is the array in which you add all of the records that you want to populate your tables. As the array key, you set your column name and as the value, the value that you want to insert into your database.

Settings
--------
You only have one setting available to you and that is the `overwrite` setting. Setting this to true will overwrite existing records that already exist if they match your data or the `replace` object if you add it.

Replace
-------
Replace is an optional array that you can add inside of your row object. If `replace` is set, then the values that you set inside of it will be used to find the row that you want removing. Replace follows the same structure as insering data, `column`, `value`.

Example
=======
`settings.json`

```json
{
    "rows": [
        {
            "id": "1",
            "name": "Created By",
            "value": "Ryan Deas"
            "replace": {
                "name": "Ryan Daes"
            }
        },
        {
            "id": 2,
            "name": "Website",
            "value": "http://supabyte.com"
        },
        {
            "id": 6,
            "name": "Estimated Next Update",
            "value": "17/11/2014",
            "replace": {
                "name": Estimated Next Update",
                "value": NULL
            }
    ],
    "settings": {
        "overwrite": true
    }
}
```
