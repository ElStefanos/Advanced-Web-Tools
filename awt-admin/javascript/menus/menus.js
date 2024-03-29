
function fetchMenus(list_element, options_element) {
    $.ajax({
        url: './jobs/menus.php',
        type: 'POST',
        data: {
            fetch_all_menus: 1
        },
        success: function (response) {
            createList(response, list_element);
            createMenuOptions(response, options_element);
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
}


function createList(menu, list_element) {

    var menu = JSON.parse(menu);

    $(list_element).empty();

    const newInput = $("<input type='text' class='input' id='menu-name' placeholder='New menu name'>");
    const newButton = $("<button>Create</button>").addClass("button");

    const create = $("<div>").addClass("create");

    newButton.click(function(e) {
        const name = $("#menu-name").val();
        if(!name.trim() == "") {
            createMenu(name);
            fetchMenus(list_element, ".menu-options");
        }
    });


    create.append(newInput);
    create.append(newButton);

    $(list_element).append(create);

    $.each(menu, function (key, value) {
        var html = $("<p data-type-menu='" + value.name + "' class='menu-cat'>" + value.name + "</p>");
        
        console.log(value);

        const selectInput = $("<input type='checkbox'>");

        selectInput.attr("data-name", value.name);

        if(value['active'] == 1) {
            html.addClass("selected");
            selectInput.attr("checked", "checked");
            selectInput.attr("disabled", "disabled");
        }

        selectInput.click(function(e) {
            const name = $(e.target).attr("data-name");
            changeActiveMenu(name, ".menu-list", ".menu-options");
        });
        
        html.append(selectInput);

        html.click(function(e){

            $(".selected").removeClass("selected");

            const menu = $(e.target).attr("data-type-menu");

            $(e.target).addClass("selected");

            $(".options-container[data-type-menu='"+ menu +"']").addClass("selected");
        });

        $(list_element).append(html);

    });
}

function createMenuOptions(menu, options_element) {
    var menu = JSON.parse(menu);

    $.each(menu, function (key, value) {


        const menu_items = value['items'].split('NEW_LINK');

        var container = $("<div class='options-container' data-type-menu='" + value.name + "'>");

        if(value.active == 1) {
            container.addClass("selected");
        }

        $.each(menu_items, function (key) {
            if (menu_items[key]) {
                const item = $(menu_items[key]);
                var optionContainer = $("<div class='option-container'>");

                var current_value = item.attr('href');

                optionContainer.append("<button class='button' id='red' onclick='DeleteItem(this);'><i class='fa-solid fa-trash'></i></button>");
                optionContainer.append("<input class='input' value='" + item.text() + "' class='option-name'>");

                fetchPages(current_value, function (selector) {
                    optionContainer.append(selector);
                });

                container.append(optionContainer);
            }
        });

        var button = "<div class='actions'><button class='button' data-type-menu='" + value.name + "' onclick='AddNew(this)'>Add New Item</button>";
        button += "<button class='button' id='green' data-type-menu='" + value.name + "' onclick='SaveChanges(this)'>Save Changes</button></div>";
        $(container).append(button);
        $(options_element).append(container);
    });

}

function fetchPages(current_value, callback) {
    $.ajax({
        url: './jobs/pages.php',
        type: 'POST',
        data: {
            getAllPages: 1
        },
        success: function (response) {
            var html = createPageSelector(response, current_value);
            callback(html);
        },
        error: function (xhr, status, error) {
            console.log(error);
            callback('');
        }
    });
}

function createPageSelector(data, current_value) {
    var pages = JSON.parse(data);
    var option = "<select class='option-name'>";
    $.each(pages, function (key, page) {
        if(page['name'] !== 'custom') {
            if (current_value && current_value.split("?page=")[1].split("&")[0] == page['name']) {
                if(page['builtIn']) {
                    option += "<option value = '?page=" + page['name'] + "' selected > " + page['name'] + "</option > ";
                } else {
                    option += "<option value = '?page=" + page['name'] + "&custom' selected > " + page['name'] + "</option > ";
                }
            } else {
                if(page['builtIn']) {
                    option += "<option value = '?page=" + page['name'] + "' > " + page['name'] + "</option > ";
                } else {
                    option += "<option value = '?page=" + page['name'] + "&custom'> " + page['name'] + "</option > ";
                }
            }
        }
    });
    option += "</select>";


    return option;
}

function changeActiveMenu(name, list_element, options_element) {
    $.ajax({
        url: './jobs/menus.php',
        type: 'POST',
        data: {
            set_active_menu: name
        },
    }).done(function(){
        fetchMenus(list_element, options_element);
    });
}

function SaveChanges(caller) {
    const dataType = $(caller).attr("data-type-menu");

    var builtMenu = [
        {
            'name': dataType,
            'items': "",
        }
    ];

    var items = "";
    $(".options-container[data-type-menu='"+ dataType +"']").find(".option-container").each(function(index, element){
        const hrefValue = $(element).find('select').val();
        const textValue = $(element).find('input').val();
    
        items += "<a href='" + hrefValue + "'>" + textValue + "</a>NEW_LINK";
    });

    builtMenu[0].items = items;

    $.ajax({
        url: './jobs/menus.php',
        type: 'POST',
        data: {
            updateMenu: 1,
            data: JSON.stringify(builtMenu)
        },
        success: function (response) {

        },
        error: function (xhr, status, error) {
            console.log(error);
            callback('');
        }
    });
}

function createMenu(name) {
    $.ajax({
        url: './jobs/menus.php',
        type: 'POST',
        data: {
            create_menu: name
        },
        success: function (response) {

        },
        error: function (xhr, status, error) {
            console.log(error);
            callback('');
        }
    });
}


function AddNew(caller) {
    var optionContainer = $("<div class='option-container'>");

    optionContainer.append("<button class='button' id='red' onclick='DeleteItem(this);'><i class='fa-solid fa-trash'></i></button>");
    optionContainer.append("<input class='input' value='New Item' class='option-name'>");

    fetchPages(undefined, function (selector) {
        optionContainer.append(selector);
    });

    optionContainer.append("</div>");

    optionContainer.insertBefore($(caller).parent());

}


function DeleteItem(caller) {
    $(caller).parent().remove();
}

$(document).ready(function () {
    fetchMenus('.menu-list', '.menu-options');
});