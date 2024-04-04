<!DOCTYPE html>
<html>
<head>
  <title>JavaScript Table</title>
  <style>
    table {
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid black;
      padding: 8px;
      text-align: left;
    }
  </style>
</head>
<body>
  <div id="table-container"></div>
<script>
var data =[{"title":"NIFTY 30 DEC 2027 | 12000 CE","kite_title_hash":"13717250","name":"NIFTY","strike":"12000","lot_size":"50","instrument_type":"CE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 12500 CE","kite_title_hash":"13717762","name":"NIFTY","strike":"12500","lot_size":"50","instrument_type":"CE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 13000 CE","kite_title_hash":"13718018","name":"NIFTY","strike":"13000","lot_size":"50","instrument_type":"CE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 13000 PE","kite_title_hash":"13718530","name":"NIFTY","strike":"13000","lot_size":"50","instrument_type":"PE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 14000 CE","kite_title_hash":"13718786","name":"NIFTY","strike":"14000","lot_size":"50","instrument_type":"CE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 14000 PE","kite_title_hash":"13719042","name":"NIFTY","strike":"14000","lot_size":"50","instrument_type":"PE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 15000 CE","kite_title_hash":"13719298","name":"NIFTY","strike":"15000","lot_size":"50","instrument_type":"CE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 15000 PE","kite_title_hash":"13720322","name":"NIFTY","strike":"15000","lot_size":"50","instrument_type":"PE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 16000 CE","kite_title_hash":"13720578","name":"NIFTY","strike":"16000","lot_size":"50","instrument_type":"CE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 16000 PE","kite_title_hash":"13721090","name":"NIFTY","strike":"16000","lot_size":"50","instrument_type":"PE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 17000 CE","kite_title_hash":"13721346","name":"NIFTY","strike":"17000","lot_size":"50","instrument_type":"CE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 17000 PE","kite_title_hash":"13722114","name":"NIFTY","strike":"17000","lot_size":"50","instrument_type":"PE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 18000 CE","kite_title_hash":"13722370","name":"NIFTY","strike":"18000","lot_size":"50","instrument_type":"CE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 18000 PE","kite_title_hash":"13722626","name":"NIFTY","strike":"18000","lot_size":"50","instrument_type":"PE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 19000 CE","kite_title_hash":"13722882","name":"NIFTY","strike":"19000","lot_size":"50","instrument_type":"CE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 19000 PE","kite_title_hash":"13723138","name":"NIFTY","strike":"19000","lot_size":"50","instrument_type":"PE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 20000 CE","kite_title_hash":"13723394","name":"NIFTY","strike":"20000","lot_size":"50","instrument_type":"CE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 20000 PE","kite_title_hash":"13724418","name":"NIFTY","strike":"20000","lot_size":"50","instrument_type":"PE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 21000 CE","kite_title_hash":"13724930","name":"NIFTY","strike":"21000","lot_size":"50","instrument_type":"CE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 21000 PE","kite_title_hash":"13725442","name":"NIFTY","strike":"21000","lot_size":"50","instrument_type":"PE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 22000 CE","kite_title_hash":"13725698","name":"NIFTY","strike":"22000","lot_size":"50","instrument_type":"CE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 22000 PE","kite_title_hash":"13725954","name":"NIFTY","strike":"22000","lot_size":"50","instrument_type":"PE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 23000 CE","kite_title_hash":"13726210","name":"NIFTY","strike":"23000","lot_size":"50","instrument_type":"CE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 23000 PE","kite_title_hash":"13726466","name":"NIFTY","strike":"23000","lot_size":"50","instrument_type":"PE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 24000 CE","kite_title_hash":"13727746","name":"NIFTY","strike":"24000","lot_size":"50","instrument_type":"CE","segment":"NFO-OPT","exchange":"NFO"},{"title":"NIFTY 30 DEC 2027 | 24000 PE","kite_title_hash":"13728002","name":"NIFTY","strike":"24000","lot_size":"50","instrument_type":"PE","segment":"NFO-OPT","exchange":"NFO"}];

data.sort(function(a, b) {
  return parseInt(a.strike) - parseInt(b.strike);
});

// Creating the table HTML
var tableHTML = '<table><thead><tr><th>Strike</th><th>Type CE</th><th>Type PE</th></tr></thead><tbody>';

// Looping through the data and creating table rows
for (var i = 0; i < data.length; i++) {
  var rowData = data[i];

  // Checking if the instrument type is CE
  if (rowData.instrument_type === "CE") {
    var peData = data.find(function(item) {
      return item.instrument_type === "PE" && item.strike === rowData.strike;
    });

    tableHTML += '<tr><td>' + rowData.strike + '</td><td>' + (rowData ? rowData.title : '') + '</td><td>' + (peData ? peData.title : '') + '</td></tr>';
  }
}

tableHTML += '</tbody></table>';


// Displaying the table on the page
document.getElementById('table-container').innerHTML = tableHTML;

</script>