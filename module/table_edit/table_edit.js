var grid;
var columns = [
    {id: "symbol", name: "Symbol", field: "kod_produktu"},
    {id: "nazwa", name: "Nazwa Produktu", field: "nazwa"},
    {id: "producent", name: "Producent", field: "producent"},
    {id: "cena_k", name: "Cena Kupna", field: "cena_kupna"},
    {id: "cena_stara", name: "Stara Cena", field: "cena_stara"},
    {id: "notatka", name: "Notatka", field: "notatka"}
];
for (var i = 0; i < columns.length; i++) {
    columns[i].header = {
        menu: {
            items: [
                {
                    iconImage: "j/SlickGrid/images/sort-asc.gif",
                    title: "Sort Ascending",
                    command: "sort-asc"
                },
                {
                    iconImage: "j/SlickGrid/images/sort-desc.gif",
                    title: "Sort Descending",
                    command: "sort-desc"
                },
                {
                    iconCssClass: "icon-help",
                    title: "Help",
                    command: "help"
                }
            ]
        }
    };
}
var options = {
  //  enableCellNavigation: true,
    enableColumnReorder: false

};

$(function () {

    var data_grid = [];
    for (var i = 0; i < 500; i++) {
        data_grid[i] = {
 kod_produktu: "Task " + i,
 nazwa: "5 days",
 producent: Math.round(Math.random() * 100),
 cena_kupna: "01/01/2009",
 cena_stara: "01/05/2009",
 notatka: (i % 5 == 0)
        };
    }
    grid = new Slick.Grid("#myGrid", data_grid, columns, options);

    var headerMenuPlugin = new Slick.Plugins.HeaderMenu({});

    headerMenuPlugin.onBeforeMenuShow.subscribe(function(e, args) {
        var menu = args.menu;

        // We can add or modify the menu here, or cancel it by returning false.
        var i = menu.items.length;
        menu.items.push({
            title: "Menu item " + i,
            command: "item" + i
        });
    });

    headerMenuPlugin.onCommand.subscribe(function(e, args) {
       alert(e.items );
    });


    grid.registerPlugin(headerMenuPlugin);
});

