<?php
if (file_exists('config.php')) {
    if (file_exists('installation.php')) {
        unlink('installation.php');
    }
}

require_once __DIR__ . '/core.php';

$serverIdRequested = 1;
if (isset($_GET['server']))
    $serverIdRequested = (int) RustStats::secureString($_GET['server']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="./css/stats.css">
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    #DataTables_Table_0_filter input {
        display: none !important;
    }

    .sc-AHaJN.fUxXJz {
        color: #fff !important;
    }

    input::placeholder {
        color: #fff;
    }

    input[type="radio"] {
        appearance: none;
        /* border: 1px solid #d3d3d3; */
        width: 20px;
        height: 20px;
        position: relative;
        content: none;
        outline: none;
        margin: 0;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }

    input[type="radio"]:checked {
        appearance: none;
        outline: none;
        padding: 0;
        content: none;
        border: none;

    }

    input[type="radio"]:checked::before {
        position: absolute;
        color: rgb(63, 63, 63) !important;
        content: "\00A0\2713\00A0" !important;
        /* border: 1px solid #d3d3d3; */
        font-weight: bolder;
        background-color: #608BC1;
    }

    input[type="radio"]::before {
        position: absolute;
        color: rgb(63, 63, 63) !important;
        content: "\00A0\2713\00A0" !important;
        /* border: 1px solid #d3d3d3; */
        font-weight: bolder;
        /* background-color: #608BC1; */
        font-size: 15px;
        border-radius: 3px;
    }

    .cTLnjL {
        justify-content: space-between !important;
        flex-direction: row !important;
    }

    .sc-cUEOzv.cTLnjL {
        background-color: rgb(30, 30, 30) !important;
        padding: 2px 5px;
    }

    .dataTables_info {
        font-size: 15px;
        text-transform: capitalize !important;
        font-weight: 400;
    }

    /* .dataTables_paginate * {
        color: #fff !important;
    } */
    .paginate_button.current {
        border-bottom: 0.3rem solid rgb(255, 126, 20) !important;
        color: rgb(63, 63, 63) !important;
    }

    .dataTables_wrapper .dataTables_paginate {
        color: #fff !important;
        font-size: 13px !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        border-bottom: 1px solid #608BC1 !important;
    }
</style>

<body>
    <div class="content-body">
        <div class="left-col">
            <div class="sc-iAEawV gnOARO">Leaderboards</div>
            <div class="sc-eeMvmM fVgaJi">
                <div class="sc-cUEOzv eFDOuZ">Search<div class="sc-bCfvAP geTXKx"><input
                            placeholder="Search by ID or username..." oninput="hideFilterInput(this)"
                            class="sc-AHaJN fUxXJz" value=""></div>
                </div>
                <div class="mb-2">Type</div>
                <ul class="nav flex-nowrap nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                            aria-selected="true">Players</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                            aria-selected="false">Clans
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                        aria-labelledby="pills-home-tab">
                        <div class="mb-2">Stats Range
                        </div>
                        <ul class="nav flex-nowrap nav-pills mb-3 notabs" id="notabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active">Lifetime

                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link">Since Wipe
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div class="mb-2">Stats Range
                        </div>
                        <ul class="nav flex-nowrap nav-pills mb-3 notabs" id="notabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active">Last wipe

                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link">Since Wipe
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php if ($config['serverSelection'] == 'enabled' && count($config['servers']) > 1) { ?>
                    <div class="mb-2">Servers</div>
                    <?php foreach ($config['servers'] as $serverId => $server) { ?>
                        <div class="sc-cUEOzv cTLnjL">
                            <option value="<?php echo $serverId; ?>" <?php if ($serverId == $serverIdRequested) {
                                   echo 'selected="selected"';
                               } ?>><?php echo $server; ?></option>
                            <label for="server">
                                <input type="radio" name="server" class="rust-stats-server-select" <?php if ($serverId == $serverIdRequested) {
                                    echo 'checked="checked"';
                                } ?> value="<?php echo $serverId; ?>">
                            </label>

                            <!-- <div class="sc-jfTVlA kdwzig">EU Main<svg aria-hidden="true" focusable="false" data-prefix="fas"
                                    data-icon="check-square" class="svg-inline--fa fa-check-square fa-w-14 " role="img"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                    <path fill="currentColor"
                                        d="M400 480H48c-26.51 0-48-21.49-48-48V80c0-26.51 21.49-48 48-48h352c26.51 0 48 21.49 48 48v352c0 26.51-21.49 48-48 48zm-204.686-98.059l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.248-16.379-6.249-22.628 0L184 302.745l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.25 16.379 6.25 22.628.001z">
                                    </path>
                                </svg></div> -->
                        </div>
                    <?php } ?>

                <?php } ?>
            </div>
        </div>
        <div class="right-col">
            <div class="gIJizS">
                <div class="sc-cwSeag hQXnMk">
                    <div class="sc-lllmON krVgnT">PVP</div>
                    <div class="sc-lllmON QrxMV">PVE</div>
                    <div class="sc-lllmON QrxMV">Gambling</div>
                    <div class="sc-lllmON QrxMV">Looted</div>
                    <div class="sc-lllmON QrxMV">Building</div>
                    <div class="sc-lllmON QrxMV">Items placed</div>
                    <div class="sc-lllmON QrxMV">Recycled</div>
                    <div class="sc-lllmON QrxMV">Gathered resources</div>
                    <div class="sc-lllmON QrxMV">Gathered food</div>
                    <div class="sc-lllmON QrxMV">Bought items</div>
                    <div class="sc-lllmON QrxMV">Explosives used</div>
                    <div class="sc-lllmON QrxMV">Fired bullets</div>
                    <div class="sc-lllmON QrxMV">Bullet hits</div>
                </div>
                <div class="content-body1">
                    <div id="pvp">
                        <div class="table-responsive">
                            <table class="table rust-stats-table">
                                <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th><?php echo $config['language']['player']; ?></th>
                                        <th><?php echo $config['language']['playtime']; ?></th>
                                        <th><?php echo $config['language']['kills']; ?></th>
                                        <th><?php echo $config['language']['deaths']; ?></th>
                                        <th><?php echo $config['language']['kdr']; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <tr class="tbody-tr">
                                    <td>#1
                                    </td>
                                    <td>
                                        <span class="sc-kgTSHT hQCHGE"><a target="_blank" rel="noopener noreferrer"
                                                href="https://steamcommunity.com/profiles/76561199554954118"
                                                to="/users/65e9a29afdd27d0013ac14d6" title="Sulfur"
                                                class="sc-fLcnxK LIWCP"><img
                                                    src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/93/931fd73e15e3f0c93ce919439fd21c2568bee03d_full.jpg"></a><span
                                                to="/users/65e9a29afdd27d0013ac14d6"
                                                class="sc-iveFHk dfoEDI ps-3">Sulfur</span></span>
                                    </td>
                                    <td>
                                        <span class="d-flex align-items-center">496 <a class="ps-3"
                                                href="/kills?attackerId=76561199554954118"><svg class="text-white"
                                                    width="18px" fill="#fff" aria-hidden="true" focusable="false"
                                                    data-prefix="fas" data-icon="eye"
                                                    class="svg-inline--fa fa-eye fa-w-18 " role="img"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                    <path fill="currentColor"
                                                        d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z">
                                                    </path>
                                                </svg></a></span>
                                    </td>
                                    <td>
                                        <span class="d-flex align-items-center">496 <a class="ps-3"
                                                href="/kills?attackerId=76561199554954118"><svg class="text-white"
                                                    width="18px" fill="#fff" aria-hidden="true" focusable="false"
                                                    data-prefix="fas" data-icon="eye"
                                                    class="svg-inline--fa fa-eye fa-w-18 " role="img"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                    <path fill="currentColor"
                                                        d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z">
                                                    </path>
                                                </svg></a></span>
                                    </td>
                                    <td>2.42</td>
                                    <td>2.42</td>
                                </tr> -->

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>

<script>
    // Function to update the URL, toggle the active class, and reload the page
    function handleTabClick(event) {
        // Get the clicked tab text and convert it to lowercase
        const tabText = event.target.textContent.toLowerCase();

        // Update the URL with the selected tab parameter, e.g., ?tab=pvp
        const url = new URL(window.location);
        url.searchParams.set('tab', tabText);
        window.history.pushState({}, '', url);

        // Remove the 'krVgnT' class (active state) from all tabs
        const tabs = document.querySelectorAll('.sc-lllmON');
        tabs.forEach(tab => {
            tab.classList.remove('krVgnT');
        });

        // Add the 'krVgnT' class to the clicked tab to mark it as active
        event.target.classList.add('krVgnT');

        // Reload the page after updating the URL to reflect the change
        window.location.reload();
    }

    // Attach the event listeners to each tab
    const tabElements = document.querySelectorAll('.sc-lllmON');
    tabElements.forEach(tab => {
        tab.addEventListener('click', handleTabClick);
    });

    // Optional: Load the current tab from the URL if it's already set
    window.addEventListener('load', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab');
        if (activeTab) {
            // Convert to uppercase or lowercase as needed when comparing
            const tabToActivate = Array.from(tabElements).find(tab => tab.textContent.toLowerCase() === activeTab);
            if (tabToActivate) {
                tabToActivate.classList.add('krVgnT');
            }
        }
    });

    function number_format(number, decimals, dec_point, thousands_sep) {
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

    $(document).ready(function () {
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
                    render: function (data, type, row) { return '<a href="https://steamcommunity.com/profiles/' + row.steamid + '" target="_blank"><img src="' + row.avatar + '" class="rust-stats-avatar"> ' + data + '</a>'; }
                },
                {
                    name: 'playtime',
                    data: 'playtime',
                    searchable: false,
                    render: function (data, type, row) { return row.playtimeProcessed; }
                },
                {
                    name: 'kills',
                    data: 'kills',
                    searchable: false,
                    render: function (data, type, row) { return number_format(data, 0); }
                },
                {
                    name: 'deaths',
                    data: 'deaths',
                    searchable: false,
                    render: function (data, type, row) { return number_format(data, 0); }
                },
                {
                    name: 'kdr',
                    data: 'kdr',
                    searchable: false,
                    render: function (data, type, row) { return number_format(data, 2); }
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
            searching: <?php if ($config['search'] == 'enabled') {
                echo 'true';
            } else {
                echo 'false';
            } ?>,
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

        <?php if ($config['serverSelection'] == 'enabled' && count($config['servers']) > 1) { ?>
            document.querySelectorAll('.rust-stats-server-select').forEach((elem) => {
                elem.addEventListener('change', () => {
                    window.location.href = window.location.pathname + '?server=' + elem.value;
                })
            });

            // document.querySelector('.rust-stats-server-select').onchange = function () {
            //     window.location.href = window.location.pathname + '?server=' + document.querySelector('.rust-stats-server-select').value;
            // };
            // $('.rust-stats-server-selection').prependTo('.dataTables_filter');
        <?php } ?>

    });
    function hideFilterInput(e) {
        var filterInput = document.querySelector('#DataTables_Table_0_filter input');
        filterInput.value = e.value;

        // Trigger the 'input' event to simulate user interaction
        var event = new Event('input', {
            'bubbles': true,
            'cancelable': true
        });

        filterInput.dispatchEvent(event);
    }

    // Example of triggering the function
    var mockEvent = { value: 'New Filter Text' }; // Example value you want to set
    hideFilterInput(mockEvent);
    // Function to update the URL and toggle active class


</script>

</html>