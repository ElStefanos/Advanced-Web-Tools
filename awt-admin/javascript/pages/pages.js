function createEmptyPage(inputName, link) {
  var val = $(inputName).val();

  $.ajax({
    url: './jobs/pages.php',
    type: 'POST',
    data: {
      createEmpty: 1,
      name: val
    },
    success: function(response) {
      console.log('AJAX request succeeded.');
      console.log(response);
      fetchPages('.pages', link); // Refresh the table after creating a new page
    },
    error: function(xhr, status, error) {
      console.log('AJAX request failed.');
      console.log(error);
    }
  });
}

function deletePage(pageId, element) {
  $.ajax({
    url: './jobs/pages.php',
    type: 'POST',
    data: {
      deletePage: 1,
      id: pageId
    },
    success: function(response) {
      console.log('AJAX request succeeded.');
      console.log(response);
      fetchPages(element); // Convert response to JSON array
    },
    error: function(xhr, status, error) {
      console.log('AJAX request failed.');
      console.log(error);
    }
  });
}

function createTable(elementId, jsonData, link) {
  var tableContainer = $(elementId);
  tableContainer.empty();

  var excludedColumns = ['token', 'content_1', 'content_2'];

  var table = $('<div>').addClass('table');

  // Create table header
  var headerRow = $('<div>').addClass('table-row');
  Object.keys(jsonData[0]).forEach(function(column) {
    if (!excludedColumns.includes(column)) {
      var headerCell = $('<div>').addClass('table-cell header-cell').text(column);
      headerRow.append(headerCell);
    }
  });

  // Add Actions header
  var actionsHeader = $('<div>').addClass('table-cell header-cell').text('Actions');
  headerRow.append(actionsHeader);

  table.append(headerRow);

  jsonData.forEach(function(rowData) {
    var dataRow = $('<div>').addClass('table-row');
    Object.entries(rowData).forEach(function([column, value]) {
      if (!excludedColumns.includes(column)) {
        var dataCell = $('<div>').addClass('table-cell data-cell').text(value);
        dataRow.append(dataCell);
      }
    });

    // Add hyperlink and button to the row
    var pageName = rowData.name;
    var pageId = rowData.id;
    var token = rowData.token;
    var editLink = $('<a>').attr('href', '?page=pageEditor&editPage=' + pageId + '&pageName=' + pageName).text('Edit');
    var liveLink = $('<a>').attr('href', link + '?page=' + pageName + "&custom").text('Visit');
    var previewLink = $('<a>').attr('href', link + '?page=' + pageName + "&custom&preview=" + token).text('Preview');
    editLink.attr('target', "_blank");
    liveLink.attr('target', "_blank");
    previewLink.attr('target', "_blank");
    var deleteButton = $('<button>').text('Delete').on('click', function() {
      deletePage(pageId, elementId);
    });

    var actionsCell = $('<div>').addClass('table-cell actions-cell');
    actionsCell.append(editLink, liveLink, previewLink, deleteButton);
    dataRow.append(actionsCell);

    table.append(dataRow);
  });

  tableContainer.append(table);
}

function fetchPages(element, link) {
  $.ajax({
    url: './jobs/pages.php',
    type: 'POST',
    data: {
      getPages: 1
    },
    success: function(response) {
      console.log('AJAX request succeeded.');
      console.log(response);
      createTable(element, JSON.parse(response), link); // Convert response to JSON array
    },
    error: function(xhr, status, error) {
      console.log('AJAX request failed.');
      console.log(error);
    }
  });
}

$(document).ready(function() {
  fetchPages('.pages');
});