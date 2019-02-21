<div  style="overflow-x: auto" >
    <table class="table table-striped table-nowrap">
        <thead id="fixedTableHead" class="table-header">

            <tr>  
                <th>
                    <?php
                    $favorite_check = "";
                    if (isset($_GET['favorite']) && $_GET['favorite'] == "true") {
                        $favorite_check = "check-";
                    }
                    ?>
                    <i  title="Filtrar Favoritos" onclick="favoriteFilter()"  class="fa fa-<?= $favorite_check; ?>square-o" style="cursor:pointer"></i> 
                </th>
                <?php
                $table_head = [
                    'name' => _e('Moeda'),
                    'rank' => _e('Rank'),
                    'price_moeda' => _e('Preço'),
                    'volume_24h_moeda' => _e('Volume 24h'),
                    'price_change_percentage_1h' => '1 ' . _e('hora'),
                    'price_change_percentage_24h' => '24 ' . _e('horas'),
                    'price_change_percentage_7d' => '7 ' . _e('dias'),
                    'price_change_percentage_14d' => '14 ' . _e('dias'),
                    'price_change_percentage_30d' => '30 ' . _e('dias'),
                    'price_change_percentage_200d' => '200 ' . _e('dias'),
                    'price_change_percentage_1y' => '1 ' . _e('ano'),
//                    'market_cap_moeda' => _e('Cap. de Mercado'),
                    'ath_change_percentage' => _e('All Time High (ATH)'),
                ];
                foreach ($table_head as $col_name => $col_desc) {
                    $class_order = '';
                    $new_order = 'desc';

                    if ($col_name == $column) {

                        if ($order == 'desc') {
                            $class_order = '-down';
                            $new_order = 'asc';
                        } elseif ($order == 'asc') {
                            $class_order = '-up';
                            $new_order = 'desc';
                        }
                    }
                    ?>
                    <th scope="col" data-order='<?= $new_order ?>' data-name='<?= $col_name; ?>' class="text-left column-order">
                        <?= $col_desc ?><i class="fa fa-sort<?= $class_order ?>"></i>
                    </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($data as $d) {

                $favorite = '-o';
                if ($d['favorite'] === $d['id_externo']) {
                    $favorite = '';
                }

                $moeda_char = $d['moeda_char'];
                if ($d['moeda_char'] == 'BTC') {
                    $moeda_char = "<span class='icon-moeda-char'><i class='fa fa-btc'></i> </span>";
                }

                //highlight volume

//                $porcVol = ($d['volume_24h_moeda'] * 100 / $max_vol24);

                $color_vol24 = volumeColor($d['volume_24h_moeda']);                
                ?>
                <tr >
                    <td class="text-left padding-table-3px" colspan="2"  style="min-width: 200px;"> 
                        <a href="javascript:addFavorite('<?= $d['id_externo'] ?>')" style="margin-right:10px;">
                            <i class="fa fa-star<?= $favorite ?>" id="user_favorite_<?= $d['id_externo'] ?>"></i>
                        </a>
                     <a href="<?= siteUrl('/currencies/' . $d['id_externo']) ?>">
                        <img style="margin-right:10px;    max-height: 20px;" src="/assets/img/coin/<?= $d['id_externo'] ?>.png">
                        <?= $d['symbol'] ?>
                        </a>
                              <?= btnBuy($d['symbol']) ?>
                    </td>
                    <td class="text-center padding-table-3px"><?= $d['rank']; ?></td>

                    <td class="text-right" style="padding-left:3px"><?= $moeda_char ?><?= decimal($d['price_moeda'], 2, true); ?></td>
                    <td class="text-right" style="background-color:  <?= $color_vol24 ?>"> <?= $moeda_char . numFormat($d['volume_24h_moeda'], 2) ?> </td>
                    <td class="text-center padding-table-3px" style="padding:0px!important"><?= formatPorc($d['price_change_percentage_1h']); ?></td>
                    <td class="text-center padding-table-3px" style="padding:0px!important"><?= formatPorc($d['price_change_percentage_24h']); ?></td>
                    <td class="text-center padding-table-3px" style="padding:0px!important"><?= formatPorc($d['price_change_percentage_7d']); ?></td>
                    <td class="text-center padding-table-3px" style="padding:0px!important"><?= formatPorc($d['price_change_percentage_14d']); ?></td>
                    <td class="text-center padding-table-3px" style="padding:0px!important"><?= formatPorc($d['price_change_percentage_30d']); ?></td>
                    <td class="text-center padding-table-3px" style="padding:0px!important"><?= formatPorc($d['price_change_percentage_200d']); ?></td>
                    <td class="text-center padding-table-3px" style="padding:0px!important"><?= formatPorc($d['price_change_percentage_1y']); ?></td>
                    <!--<td class="text-center"><span<?= tooltip('$' . decimal($d['market_cap_moeda'], 0)) ?>>$<?= numFormat($d['market_cap_moeda'], 2); ?></span></td>-->
                    <!--<td class="text-center padding-table-3px"><?= formatPorc($d['ath_change_percentage'], $d['high_price'], $moeda_char, $d['high_date']); ?></td>-->
                    <td class="text-center"data-toggle="tooltip" title="
                        <?= $d['symbol'] ?><br/> 
                        <?= decimal($d['ath_change_percentage'], 2); ?>%<br/>
                        <?= $moeda_char . decimalAuto($d['high_price']); ?> <br/>
                        <?= $d['high_date'] ?>
                        " data-html='true'>
                        <div class="progress progress-line-primary ">
                            <div class="progress-bar progress-bar-primary" role="progressbar" style="width: <?= round(100 + $d['ath_change_percentage'], 2) ?>%;">
                            </div>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<div class="row" style="padding:20px">
    <?php
    if (isset($data[0]['data_alteracao'])) {
        ?>
        <div class="col-md-4  ">
            <?= _e('Ultima atualização') . ' ' . dateDesc($data[0]['data_alteracao']) ?>
        </div>
        <?php
    }
    if (count($data) >= $limit || $page > 0) {
        ?>
        <div class="col-md-4 ml-auto  text-right " style="margin-bottom:15px">
            <?php
            $disabledPrev = '';
            $disabledNext = '';
            if ($page == 0) {
                $disabledPrev = 'disabled';
            }
            if (count($data) < $limit) {
                $disabledNext = 'disabled';
            }
            ?>
            <button type="button" onclick="loadPage(<?= $page - 1 ?>)" class="btn btn-primary btn-round" <?= $disabledPrev ?>>< <?= _e('Anterior') ?></button>

            <button type="button" onclick="loadPage(<?= $page + 1 ?>)" class="btn btn-primary btn-round" <?= $disabledNext ?>><?= _e('Próximo') ?> > </button>

        </div>
    <?php } ?>
</div>
<script>
    $('.column-order').on('click', function () {
        var name = $(this).data('name');
        var order = $(this).data('order');

        $("#order_name").val(name);
        $("#order_type").val(order);

        loadPage();
    });

    $("#min_rank").val('<?= $min_rank; ?>');
    $("#max_rank").val('<?= $max_rank; ?>');

    $('[data-toggle="tooltip"]').tooltip();

//    $('.JStableOuter table').scroll(function (e) {
//
//        $('.JStableOuter thead').css("left", -$(".JStableOuter tbody").scrollLeft());
//        $('.JStableOuter thead th:nth-child(2)').attr("style","z-index:1000");
//        $('.JStableOuter thead th:nth-child(2)').css("left", $(".JStableOuter table").scrollLeft() - 0);
//        $('.JStableOuter thead th:nth-child(1)').css("left", $(".JStableOuter table").scrollLeft() - 0);
//        $('.JStableOuter tbody td:nth-child(1)').css("left", $(".JStableOuter table").scrollLeft());
//
//        $('.JStableOuter thead').css("top", -$(".JStableOuter tbody").scrollTop());
//        $('.JStableOuter thead tr th').css("top", $(".JStableOuter table").scrollTop());
//
//    });

    var header = $("#fixedTableHead");
    var position = header.offset().top;

    tableHeadFixed();

    window.onscroll = function () {
        tableHeadFixed();
    };

    function tableHeadFixed() {
        var top = window.pageYOffset;
        var header = $("#fixedTableHead");
        if (top > position) {
            header.attr("style", "transform: translate(0px," + (top - position) + "px);");
        } else {
            header.removeAttr("style");
        }
    }

</script>
<style>
    #fixedTableHead {
        z-index: 1000;
    }

</style>
