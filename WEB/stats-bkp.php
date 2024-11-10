<?php
    if(file_exists('config.php')) {
        if(file_exists('installation.php')) {
            unlink('installation.php');
        }
    }

    require_once __DIR__ . '/core.php';

    $serverIdRequested = 1;
    if(isset($_GET['server']))
	    $serverIdRequested = (int) RustStats::secureString($_GET['server']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rust Stats</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/<?php echo $config['theme']; ?>.css">
</head>
<body>

<div class="rust-stats">
    <?php if($config['serverSelection'] == 'enabled' && count($config['servers']) > 1) { ?>
        <div class="rust-stats-server-selection">
            <select class="rust-stats-server-select" name="server">
                <?php foreach($config['servers'] as $serverId => $server) { ?>
                    <option value="<?php echo $serverId; ?>" <?php if($serverId == $serverIdRequested) { echo 'selected="selected"'; } ?>><?php echo $server; ?></option>
                <?php } ?>
            </select>
        </div>
    <?php } ?>
    <table class="rust-stats-table">
        <thead>
        <tr>
            <th class="rust-stats-rank-heading">&nbsp;</th>
            <th class="rust-stats-player-heading">
                <svg class="rust-stats-heading-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path fill="currentColor" d="M272 304h-96C78.8 304 0 382.8 0 480c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32C448 382.8 369.2 304 272 304zM48.99 464C56.89 400.9 110.8 352 176 352h96c65.16 0 119.1 48.95 127 112H48.99zM224 256c70.69 0 128-57.31 128-128c0-70.69-57.31-128-128-128S96 57.31 96 128C96 198.7 153.3 256 224 256zM224 48c44.11 0 80 35.89 80 80c0 44.11-35.89 80-80 80S144 172.1 144 128C144 83.89 179.9 48 224 48z"/>
                </svg>
				<?php echo $config['language']['player']; ?>
            </th>
            <th class="rust-stats-playtime-heading">
                <svg class="rust-stats-heading-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="currentColor" d="M232 120C232 106.7 242.7 96 256 96C269.3 96 280 106.7 280 120V243.2L365.3 300C376.3 307.4 379.3 322.3 371.1 333.3C364.6 344.3 349.7 347.3 338.7 339.1L242.7 275.1C236 271.5 232 264 232 255.1L232 120zM256 0C397.4 0 512 114.6 512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0zM48 256C48 370.9 141.1 464 256 464C370.9 464 464 370.9 464 256C464 141.1 370.9 48 256 48C141.1 48 48 141.1 48 256z"/>
                </svg>
				<?php echo $config['language']['playtime']; ?>
            </th>
            <th class="rust-stats-kills-heading">
                <svg class="rust-stats-heading-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="currentColor" d="M224 256C224 238.3 238.3 224 256 224C273.7 224 288 238.3 288 256C288 273.7 273.7 288 256 288C238.3 288 224 273.7 224 256zM256 0C273.7 0 288 14.33 288 32V42.35C381.7 56.27 455.7 130.3 469.6 224H480C497.7 224 512 238.3 512 256C512 273.7 497.7 288 480 288H469.6C455.7 381.7 381.7 455.7 288 469.6V480C288 497.7 273.7 512 256 512C238.3 512 224 497.7 224 480V469.6C130.3 455.7 56.27 381.7 42.35 288H32C14.33 288 0 273.7 0 256C0 238.3 14.33 224 32 224H42.35C56.27 130.3 130.3 56.27 224 42.35V32C224 14.33 238.3 0 256 0V0zM224 404.6V384C224 366.3 238.3 352 256 352C273.7 352 288 366.3 288 384V404.6C346.3 392.1 392.1 346.3 404.6 288H384C366.3 288 352 273.7 352 256C352 238.3 366.3 224 384 224H404.6C392.1 165.7 346.3 119.9 288 107.4V128C288 145.7 273.7 160 256 160C238.3 160 224 145.7 224 128V107.4C165.7 119.9 119.9 165.7 107.4 224H128C145.7 224 160 238.3 160 256C160 273.7 145.7 288 128 288H107.4C119.9 346.3 165.7 392.1 224 404.6z"/>
                </svg>
				<?php echo $config['language']['kills']; ?>
            </th>
            <th class="rust-stats-deaths-heading">
                <svg class="rust-stats-heading-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="currentColor" d="M416 400V464C416 490.5 394.5 512 368 512H320V464C320 455.2 312.8 448 304 448C295.2 448 288 455.2 288 464V512H224V464C224 455.2 216.8 448 208 448C199.2 448 192 455.2 192 464V512H144C117.5 512 96 490.5 96 464V400C96 399.6 96 399.3 96.01 398.9C37.48 357.8 0 294.7 0 224C0 100.3 114.6 0 256 0C397.4 0 512 100.3 512 224C512 294.7 474.5 357.8 415.1 398.9C415.1 399.3 416 399.6 416 400V400zM160 192C124.7 192 96 220.7 96 256C96 291.3 124.7 320 160 320C195.3 320 224 291.3 224 256C224 220.7 195.3 192 160 192zM352 320C387.3 320 416 291.3 416 256C416 220.7 387.3 192 352 192C316.7 192 288 220.7 288 256C288 291.3 316.7 320 352 320z"/>
                </svg>
				<?php echo $config['language']['deaths']; ?>
            </th>
            <th class="rust-stats-kdr-heading">
                <svg class="rust-stats-heading-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path fill="currentColor" d="M160 80C160 53.49 181.5 32 208 32H240C266.5 32 288 53.49 288 80V432C288 458.5 266.5 480 240 480H208C181.5 480 160 458.5 160 432V80zM0 272C0 245.5 21.49 224 48 224H80C106.5 224 128 245.5 128 272V432C128 458.5 106.5 480 80 480H48C21.49 480 0 458.5 0 432V272zM400 96C426.5 96 448 117.5 448 144V432C448 458.5 426.5 480 400 480H368C341.5 480 320 458.5 320 432V144C320 117.5 341.5 96 368 96H400z"/>
                </svg>
				<?php echo $config['language']['kdr']; ?>
            </th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>

</div>

<script>

    function number_format (number, decimals, dec_point, thousands_sep) {
        // Strip all characters but numerical ones.
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    $(document).ready( function () {
        let statsTable = $('.rust-stats-table').DataTable({
            columns: [
                {
                    name: 'id',
                    orderable: false,
                    searchable: false,
                    data: null
                },
                {
                    name: 'name',
                    data: 'name',
                    orderable: false,
                    render: function ( data, type, row ) { return '<a href="https://steamcommunity.com/profiles/'+row.steamid+'" target="_blank"><img src="'+row.avatar+'" class="rust-stats-avatar"> '+data+'</a>'; }
                },
                {
                    name: 'playtime',
                    data: 'playtime',
                    searchable: false,
                    render: function ( data, type, row ) { return row.playtimeProcessed; }
                },
                {
                    name: 'kills',
                    data: 'kills',
                    searchable: false,
                    render: function ( data, type, row ) { return number_format(data, 0); }
                },
                {
                    name: 'deaths',
                    data: 'deaths',
                    searchable: false,
                    render: function ( data, type, row ) { return number_format(data, 0); }
                },
                {
                    name: 'kdr',
                    data: 'kdr',
                    searchable: false,
                    render: function ( data, type, row ) { return number_format(data, 2); }
                }
            ],
            processing: true,
            serverSide: true,
            ajax: {
                url: 'data.php',
                data: {
                    server: <?php echo $serverIdRequested; ?>,
                }
            },
            order: [[<?php echo $config['orderBy']; ?>, 'desc']],
            pageLength: <?php echo $config['pagination']; ?>,
            searching: <?php if($config['search'] == 'enabled') { echo 'true'; } else { echo 'false'; } ?>,
            bLengthChange: false,
            language: {
                search: '',
                zeroRecords: '<?php echo $config['language']['no_players']; ?>',
                emptyTable: '<?php echo $config['language']['no_players']; ?>',
                searchPlaceholder: '<?php echo $config['language']['search']; ?>...',
                info: '<?php echo $config['language']['players_stats']; ?>',
                infoEmpty: '<?php echo $config['language']['players_stats_empty']; ?>',
                infoFiltered: '<?php echo $config['language']['players_stats_filtered']; ?>',
                paginate: {
                    first: '<?php echo $config['language']['paginate_first']; ?>',
                    last: '<?php echo $config['language']['paginate_last']; ?>',
                    next: '<?php echo $config['language']['paginate_next']; ?>',
                    previous: '<?php echo $config['language']['paginate_previous']; ?>'
                },
            }
        });

        // Add the index column to each row
        statsTable.on('draw.dt', function () {
            let info = statsTable.page.info();
            statsTable.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + info.start;
            });
        });

        $('.dataTables_filter input').attr('type', 'text');
        $('.dataTables_filter input').attr('autocomplete', 'off');

	    <?php if($config['serverSelection'] == 'enabled' && count($config['servers']) > 1) { ?>
            document.querySelector('.rust-stats-server-select').onchange = function(){
                window.location.href = window.location.pathname + '?server=' + document.querySelector('.rust-stats-server-select').value;
            };
            $('.rust-stats-server-selection').prependTo('.dataTables_filter');
	    <?php } ?>

    } );
</script>
</body>
</html>