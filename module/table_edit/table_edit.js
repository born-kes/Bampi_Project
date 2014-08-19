
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
var grid,
    options = {
        editable: true,
        enableAddRow: true,
    enableCellNavigation: true,
    enableColumnReorder: false,
        asyncEditorLoading: true,
        autoEdit: true
    },
    numberOfItems = 25000,
    items = [], indices,
    isAsc = true,
    currentSortCol = { id: "title" },
    i;


 var data = [];
 /* for (var i = 0; i < 500; i++) {
 data[i] = {
 kod_produktu: "Task " + i,
 nazwa: "5 days",
 producent: Math.round(Math.random() * 100),
 cena_kupna: "01/01/2009",
 cena_stara: "01/05/2009",
 notatka: (i % 5 == 0)
 };
 }*/

// Copies and shuffles the specified array and returns a new shuffled array.
function randomize(items) {
    var randomItems = $.extend(true, null, items), randomIndex, temp, index;
    for (index = items.length; index-- > 0;) {
        randomIndex = Math.round(Math.random() * items.length - 1);
        if (randomIndex > -1) {
            temp = randomItems[randomIndex];
            randomItems[randomIndex] = randomItems[index];
            randomItems[index] = temp;
        }
    }
    return randomItems;
}
/// Build the items and indices.
for (i = numberOfItems; i-- > 0;) {
    items[i] = i;
    data[i] = {
        kod_produktu: "Task ".concat(i + 1)
    };
}
indices = { title: items, nazwa: randomize(items), producent: randomize(items), cena_kupna: randomize(items) };
// Assign values to the data.
for (i = numberOfItems; i-- > 0;) {
    data[indices.nazwa[i]].nazwa = "nazwa ".concat(i + 1);
    data[indices.producent[i]].producent = "producent ".concat(i + 1);
    data[indices.cena_kupna[i]].cena_kupna = "cena_kupna ".concat(i + 1);
}

// Define function used to get the data and sort it.
function getItem(index) {
    return isAsc ? data[indices[currentSortCol.id][index]] : data[indices[currentSortCol.id][(data.length - 1) - index]];
}
function getLength() {
    return data.length;
}


$(function () {

    grid = new Slick.Grid("#myGrid", {getLength: getLength, getItem: getItem}, columns, options);
    grid.onSort.subscribe(function (e, args) { alert('sort');
        currentSortCol = args.sortCol;
        isAsc = args.sortAsc;
        grid.invalidateAllRows();
        grid.render();
    });


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
  //  grid.registerPlugin(headerMenuPlugin);
});

