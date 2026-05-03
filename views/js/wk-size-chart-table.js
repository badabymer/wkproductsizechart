/**
* 2010-2019 Webkul.
*
* NOTICE OF LICENSE
*
* All right is reserved,
* Please go through this link for complete license : https://store.webkul.com/license.html
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize this module for your
* needs please refer to https://store.webkul.com/customisation-guidelines/ for more information.
*
*  @author    Webkul IN <support@webkul.com>
*  @copyright 2010-2019 Webkul IN
*  @license   https://store.webkul.com/license.html
*/

$(document).ready(function () {
    $('.wk_filter_product_errors').hide();
    $('#wk_src_btn').after("<input type='hidden' id='wk_check_filter' value=1>");
    if (!(typeof popUpView != 'undefined' && popUpView)) {
        $('#size-chart-view').hide();
        $('#size-chart-button').click(function () {
            if (!$('#size-chart-view').is(':visible')) {
                $('#size-chart-arrow').text("keyboard_arrow_up");
            } else {
                $('#size-chart-arrow').text("keyboard_arrow_down");
            }
            $('#size-chart-view').slideToggle("fast");
        });
    }
    if (!(typeof editBulkAction != 'undefined' && editBulkAction)) {
        $("#wk-search-result").hide();
        $("#wk_check_filter").val(0);
    }
    $('#search_product_name, #plan-categories, #id_suppliers___chosen, #id_manufacturers___chosen').click(function () {
        $("#wk_check_filter").val(0);
    });

    $('[name^=submitAddwk_size_chart_filter]').on("click", function (event) {
        validateFilters();
        if ($("#wk_check_filter").val() == 0) {
            event.preventDefault();
            event.stopPropagation();
            return $.growl.error({
                title: "",
                size: "large",
                message: errorMsg['clickSearch']
            });
        }

        if ($('[name="searchedProduct[]"]').length) {
            if (typeof $('[name="searchedProduct[]"]:checked').val() == 'undefined') {
                event.preventDefault();
                event.stopPropagation();
                return $.growl.error({
                    title: "",
                    size: "large",
                    message: errorMsg['productRequired']
                });
            }
        } else {
            event.preventDefault();
            event.stopPropagation();
            return $.growl.error({
                title: "",
                size: "large",
                message: errorMsg['productRequired']
            });
        }

        if ($('[name=size_chart_list]').val() == 0) {
            event.preventDefault();
            event.stopPropagation();
            return $.growl.error({
                title: "",
                size: "large",
                message: errorMsg['chartRequired']
            });
        }
    });

    if (typeof WkProductSizeChart != 'undefined' && WkProductSizeChart) {
        if ($("input[name=apply_chart]:checked").val() == 1) {
            $('[name=size_chart_list]').parents("div.form-group:first").show();
            $('#size_chart_table').parent().show();
        } else {
            $('[name=size_chart_list]').val("0");
            $('[name=size_chart_list]').parents("div.form-group:first").hide();
            $('#size_chart_table').parents(".form-group:first").hide();
        }

        $("input[name=apply_chart]").on("change", function () {
            if ($(this).val() == 1) {
                $('[name=size_chart_list]').parents("div.form-group:first").show();
                $('#size_chart_table').parent().show();
            } else {
                $('[name=size_chart_list]').val("0");
                $('[name=size_chart_list]').parents("div.form-group:first").hide();
                $('#size_chart_table').parents(".form-group:first").hide();
            }
        });

        $('[name=size_chart_list]').on('change', function() {
            if ($(this).val() != '0') {
                idSizeChart = $(this).val();
                $.ajax({
                    url: WkProductSizeChart,
                    type: 'POST',
                    cache: false,
                    dataType: 'json',
                    data: {
                        ajax: true,
                        action: 'displayProductSizeChart',
                        idSizeChart: idSizeChart
                    },
                    success: function (response) {
                        if (response) {
                            $('#size_chart_table').parents(".form-group:first").show();
                            $('#size_chart_table').html(response);
                        }
                    },
                });
            } else {
                $('#size_chart_table').html('');
            }
        });
    }

    $('#wk_src_btn').on('click', function() {
        if (validateFilters() === true) {
            srcPattern = $('#search_product_name').val();
            var idCategories = [];
            $.each($('[name="wk_id_categories[]"]:checked'), function () {
                idCategories.push($(this).val());
            });
            idSuppliers = $('[name="id_suppliers[]"]').val();
            idManufacturers = $('[name="id_manufacturers[]"]').val();
            $.ajax({
                url: WkSizeChartFilter,
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: {
                    ajax: true,
                    action: 'displayFilteredProducts',
                    srcPattern: srcPattern,
                    idCategories: idCategories,
                    idSuppliers: idSuppliers,
                    idManufacturers: idManufacturers,
                },
                success: function (response) {
                    if (response) {
                        $("#wk-search-result").show();
                        $("#wk_check_filter").val(1);
                        $('.wk_filter_product_errors').hide();
                    } else {
                        $('.wk_filter_product_errors').text(errorMsg['productNotFound']);
                        $('.wk_filter_product_errors').show();
                        $("#wk-search-result").hide();
                    }
                    $('#wk-filtered-list').html(response);
                },
            });
        }
    });
});

function validateFilters()
{
    var idCategories = [];
    $.each($('[name="wk_id_categories[]"]:checked'), function () {
        idCategories.push($(this).val());
    });
    if ($('#search_product_name').val() != "") {
        if (!$("#search_product_name").val().match(/^[^<>;=#{}]*$/)) {
            event.preventDefault();
            event.stopPropagation();
            return $.growl.error({
                title: "",
                size: "large",
                message: errorMsg['patternInvalid']
            });
        }
        if ($("#search_product_name").val().length < 3) {
            event.preventDefault();
            event.stopPropagation();
            return $.growl.error({
                title: "",
                size: "large",
                message: errorMsg['patternLength']
            });
        }
    }
    if ($('#search_product_name').val().trim() == "" &&
        idCategories.length == 0 &&
        $('[name="id_manufacturers[]"]').val() == null &&
        $('[name="id_suppliers[]"]').val() == null) {
        event.preventDefault();
        event.stopPropagation();
        return $.growl.error({
            title: "",
            size: "large",
            message: errorMsg['oneFilterRequired']
        });
    }

    return true;
}
