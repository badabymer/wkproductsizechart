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

var chart_type = predefined_type;
var id_attribute_group = 0;
var attributeNames = [];
$(document).ready(function() {
    //On page load
    if ($(window).width() < 450) {
        oldWidth = 200;
        calcWidth = 1;
    } else if ($(window).width() < 550) {
        oldWidth = 300;
        calcWidth = 2;
    } else if ($(window).width() < 650) {
        oldWidth = 600;
        calcWidth = 4;
    } else if ($(window).width() < 750) {
        oldWidth = 800;
        calcWidth = 5;
    } else if ($(window).width() > 750) {
        oldWidth = 1000;
        calcWidth = 7;
    }
    maxCustomId = maxIdCustom;
    $("#custom_attribute").after('<input type="text" name="wk_custom_attribute_id" id="wk_custom_attribute_id" class="hidden">');
    $("#id_attribute_group").after('<input type="text" name="wk_predefined_attribute_id" id="wk_predefined_attribute_id" class="hidden">');

    $("#custom_attribute").parents(".form-group:first").next().attr("id", "show_attribute");
    $("#id_attribute_group option:first").attr("disabled", "disabled");
    $(".wk-size-chart-data").parent().removeClass('col-lg-9 col-lg-offset-3');
    if (typeof editPage != 'undefined' && editPage) {
        if (typeof attributeType != 'undefined' && attributeType) {
            chart_type = attributeType;
        }
        newWidth = oldWidth + ((totalAttribute - calcWidth) * 90);
        setNewWidth(newWidth);
        if (totalAttribute >= calcWidth) {
            $('#wk_size_chart_container').addClass('wk-size-chart-container');
        } else {
            $('#wk_size_chart_container').removeClass('wk-size-chart-container');
        }
        $("#custom_attribute").parents(".form-group:first").addClass("hidden");
        toggleHideOnEdit();
        $("input[name=size_chart_type]").on("change", function() {
            if ($(this).val() == chart_type) {
                if ($('#id_attribute_group').find(":selected").val() == predefined_type) {
                    $("#show_attribute").removeClass("hidden");
                    $("#custom_attribute").parents(".form-group:first").addClass("hidden");
                } else {
                    $("#show_attribute").addClass("hidden");
                    $("#custom_attribute").parents(".form-group:first").removeClass("hidden");
                }
                toggleHideOnEdit();
            } else {
                $('#id_attribute_group').val('0');
                toggleHideAttributes();
            }
            if ($(this).val() == predefined_type) {
                var id_attribute_group = $('#id_attribute_group').find(":selected").val();
                $('#id_attribute_group option[value="' + id_attribute_group + '"]').attr("selected", "selected");
            }
        });
        if ($("input[name=size_chart_type]:checked").val() == predefined_type) {
            attributeNames = attributeId;
            $('#wk_predefined_attribute_id').val(attributeNames);
            displayCheckbox(attributeId);
        } else {
            attributeNames = [];
            $(".wk-attr-" + defaultLangId).each(function() {
                attributeNames.push($(this).children("input").val());
            });
            $("#custom_attribute").val(attributeNames);
            $('#wk_custom_attribute_id').val(attributeId.join(","));
        }
        if (typeof deleteImageLink != 'undefined' && deleteImageLink) {
            $("#image-images-thumbnails div").after('<a href="' + deleteImageLink + '" id="delete_image" class="btn btn-default"><i class="icon-trash-o"></i></a>');
        }
    } else {
        toggleHideAttributes();
        $("input[name=size_chart_type]").on("change", function() {
            $("#id_attribute_group").val(predefined_type);
            $("#attribute_checkbox").remove();
            toggleHideAttributes();
            $("#custom_attribute").val("");
        });
    }

    $("#id_attribute_group").on("change", function() {
        totalAttribute = 0;
        $('.wk-row-width').width(oldWidth);
        id_attribute_group = $("#id_attribute_group").val();
        $("#show_attribute").addClass("hidden");
        $("#attribute_checkbox").remove();
        $(".wk-measurement-row").remove();
        $("[name^=pre_attributes_value]").parent().remove();
        $("#wk_predefined_attribute_id").val("");
        attributeNames = [];
        displayCheckbox(0);
    });

    $("#create_custom_attribute").on("click", function() {
        if (!$("#custom_attribute").val().match(/^[^<>={}]*$/)) {
            event.preventDefault();
            event.stopPropagation();
            return $.growl.error({
                title: "",
                size: "large",
                message: errorMsg['customInvalid']
            });
        }
        chart_type = $("input[name=size_chart_type]:checked").val();
        $("#id_attribute_group").val('0');
        $("#attribute_checkbox").remove();
        customNameValue = $("#custom_attribute").val();
        if (customNameValue.indexOf(',') > -1) {
            countAttributeName = customNameValue.match(/,/gi).length;
        } else {
            event.preventDefault();
            event.stopPropagation();
            return $.growl.error({
                title: "",
                size: "large",
                message: errorMsg['commaRequired']
            });
        }
        $("#wk_custom_attribute_id").val("");
        attributeId = $("#wk_custom_attribute_id").val();
        totalAttribute = 0;
        $('.wk-row-width').width(oldWidth);
        for (i = 0; i <= countAttributeName; i++) {
            totalAttribute++;
            if (i == countAttributeName) {
                $('#wk_custom_attribute_id').val($("#wk_custom_attribute_id").val());
                attributeId = attributeId + maxCustomId;
            } else {
                $('#wk_custom_attribute_id').val($("#wk_custom_attribute_id").val() + ",");
                attributeId = attributeId + maxCustomId + ',';
            }
            maxCustomId++;
        }
        if (totalAttribute >= calcWidth) {
            $('#wk_size_chart_container').addClass('wk-size-chart-container');
            newWidth = $('.wk-row-width').width() + 90;
            setNewWidth(newWidth);
        } else {
            $('#wk_size_chart_container').removeClass('wk-size-chart-container');
        }

        attributeIds = $("#wk_custom_attribute_id").val();
        $(".wk-measurement-row").remove();
        if (attributeIds) {
            setSizeChartAttribute(attributeIds, true);
        }
    });

    $(document).on("click", "#back", function() {
        var confirmBack = confirm('You will lose all unsaved measurements. Are you sure that you want to proceed?');
        if (confirmBack == true) {
            maxCustomId = maxIdCustom;
            if (($("input[name=size_chart_type]:checked").val() == custom_type)) {
                $("#custom_attribute").parents(".form-group:first").removeClass("hidden");
                $("#show_attribute").addClass("hidden");
            }
        }
    });

    $(document).on("click", ".attribute_list", function() {
        var id_attribute = $(this).val();
        var attribute_name = $(this).data('name');
        if ($(this).prop("checked") == true) {
            attributeNames.push($(this).val());
            chart_type = predefined_type;
            $('#wk_predefined_attribute_id').val(attributeNames);
            addBlankColumn(id_attribute, attribute_name, chart_type);
        } else {
            $('.wk_attr_heading_' + id_attribute).remove();
            $('.wk_attr_value_' + id_attribute).remove();
            totalAttribute--;
            attributeNames = $.grep(attributeNames, function(value) {
                return value != id_attribute;
            });
            if (totalAttribute < calcWidth) {
                $('#wk_size_chart_container').removeClass('wk-size-chart-container');
            } else {
                newWidth = $('.wk-row-width').width() - 90;
                setNewWidth(newWidth);
            }
        }
        $('#wk_predefined_attribute_id').val(attributeNames);
    });
    $(document).on("click", "#add_column_btn", function() {
        chart_type = custom_type;
        addBlankColumn(maxCustomId, "", chart_type);
        maxCustomId++;
    });

    $(document).on("click", "#add_row_btn", function() {
        if ($("input[name=size_chart_type]:checked").val() == predefined_type) {
            nameAttribute = $('#wk_predefined_attribute_id').val().split(',');
            isCustom = false;
        } else {
            nameAttribute = $("#wk_custom_attribute_id").val();
            if ($.isArray(attributeId)) {
                nameAttribute = attributeId.join(',');
            } else {
                nameAttribute = attributeId;
            }
            isCustom = true;
        }
        addBlankRow(nameAttribute, isCustom);
    });

    $("[name^=submitAddwk_size_chart]").on("click", function(event) {
        if ($("#title_" + defaultLangId).val().trim() == "") {
            event.preventDefault();
            event.stopPropagation();
            return $.growl.error({
                title: "",
                size: "large",
                message: errorMsg['titleRequired']
            });
        }
        if (!$("#title_" + defaultLangId).val().match(/^[^<>={}]*$/)) {
            event.preventDefault();
            event.stopPropagation();
            return $.growl.error({
                title: "",
                size: "large",
                message: errorMsg['titleInvalid']
            });
        }
        if ($("#wk_size_chart_form #image").val()) {
            var ext = $("#wk_size_chart_form #image").val().split('.').pop().toLowerCase();
            if ($.inArray(ext, ['png', 'jpg', 'jpeg', 'webp']) == -1) {
                event.preventDefault();
                event.stopPropagation();
                return $.growl.error({
                    title: "",
                    size: "large",
                    message: errorMsg['imageInvalid']
                });
            }
            if (typeof maxSizeByte !== 'undefined') {
                if ($('#image')[0].files[0] !== undefined) {
                    if ($('#image')[0].files[0].size > maxSizeByte) {
                        event.preventDefault();
                        event.stopPropagation();
                        return $.growl.error({
                            title: "",
                            size: "large",
                            message: errorMsg['imageSize']
                        });
                    }
                }
            }
        }

        var selectedType = $("input[name=size_chart_type]:checked").val();
        if (typeof image_type !== 'undefined' && selectedType == image_type) {
            // Image type — no attribute validation needed
        } else if (selectedType == predefined_type) {
            if (!$("#id_attribute_group").val()) {
                event.preventDefault();
                event.stopPropagation();
                return $.growl.error({
                    title: "",
                    size: "large",
                    message: errorMsg['selectRequired']
                });
            }
            if (!$(".attribute_list:checked").val()) {
                event.preventDefault();
                event.stopPropagation();
                return $.growl.error({
                    title: "",
                    size: "large",
                    message: errorMsg['chooseRequired']
                });
            }
        } else {
            if ($("#custom_attribute").val().trim() == "") {
                event.preventDefault();
                event.stopPropagation();
                return $.growl.error({
                    title: "",
                    size: "large",
                    message: errorMsg['customRequired']
                });
            }
        }

        $("[name^=pre_attributes_value]").each(function() {
            if (!$(this).val().match(/^[^<>={}]*$/)) {
                event.preventDefault();
                event.stopPropagation();
                return $.growl.error({
                    title: "",
                    size: "large",
                    message: errorMsg['attributeInvalid']
                });
            }
        });

        $("[name^=measurement]").each(function() {
            if (!$(this).val().match(/^[^<>={}]*$/)) {
                event.preventDefault();
                event.stopPropagation();
                return $.growl.error({
                    title: "",
                    size: "large",
                    message: errorMsg['measurementInvalid']
                });
            }
        });

        totalMeasure = $('#total_measurement').val();
        if (totalMeasure != 0) {
            for (count = 1; count <= totalMeasure; count++) {
                if ($("[name=measurement" + count + "_" + defaultLangId + "]").length) {
                    if ($("[name=measurement" + count + "_" + defaultLangId + "]").val().trim() == "") {
                        event.preventDefault();
                        event.stopPropagation();
                        return $.growl.error({
                            title: "",
                            size: "large",
                            message: errorMsg['measurementRequired']
                        });
                    }
                }
            }
        }
    });

    if (typeof totalMeasurement == 'undefined') {
        var count = 0;
        totalMeasurement = 0;
    } else {
        count = totalMeasurement;
    }

    $("#show_attribute").after('<input type="hidden" value="' + totalMeasurement + '" name="total_measurement" id="total_measurement">');

    $(document).on("click", "[id^=delete_row_btn]", function() {
        $(this).parents(".wk-measurement-row:first").remove();
    });

    function addBlankColumn(id_attribute, attribute_name, chart_type) {
        if (typeof totalAttribute == 'undefined') {
            totalAttribute = 0;
            $('.wk-row-width').width(oldWidth);
            if ($("#custom_attribute").val()) {
                $(".wk-attr-" + defaultLangId).each(function() {
                    totalAttribute++;
                });
            }
            totalAttribute++;;
        } else {
            totalAttribute++;
        }
        if (totalAttribute >= calcWidth) {
            $('#wk_size_chart_container').addClass('wk-size-chart-container');
            newWidth = $('.wk-row-width').width() + 90;
            setNewWidth(newWidth);
        }
        $('#show_attribute').removeClass("hidden");
        $('.wk-size-chart-lang-btn').each(function() {
            var id_lang = $(this).data('lang-id');
            if (!chart_type) {
                attributeName = attribute_name[id_lang];
            } else {
                attributeName = attribute_name;
            }
            var attribute_html = '<div class="wk-float-left wk-col-margin wk_attr_heading_' + id_attribute + ' wk-attr-' + id_lang + '"><input type="text" name="pre_attributes_value' + totalAttribute + '_' + id_lang + '" class="fixed-width-sm wk-border-radius" value="' + attributeName + '"></div>';
            $(this).before(attribute_html);
        });
        if (!chart_type) {
            $('[name^=pre_attributes_value]').attr('readonly', 'readonly');
        }
        $('.wk-delete-measurement').each(function() {
            var measure_index = $(this).data('measure-index');
            var measure_html = '<div class="wk-float-left wk-col-margin wk_attr_value_' + id_attribute + '"><input type="text" name="measurement_value' + measure_index + '_' + id_attribute + '" class="fixed-width-sm wk-border-radius"></div >';
            $(this).before(measure_html);
        });

        if (chart_type) {
            attributeIds = $("#wk_custom_attribute_id").val() + ',';
            $("#wk_custom_attribute_id").val(attributeIds);
            if ($.isArray(attributeId)) {
                attributeId = attributeId.join(',') + ',' + id_attribute;
            } else {
                attributeId = attributeId + ',' + id_attribute;
            }
        }
    }

    function addBlankRow(nameAttribute, isCustom)
    {
        var total = $('#total_measurement').val();
        $('#total_measurement').val(parseInt(total) + 1);
        count = count + 1;
        $.ajax({
            url: WkSizeChartLink,
            type: "POST",
            cache: false,
            dataType: 'json',
            data: {
                ajax: true,
                action: "addBlankRows",
                nameAttribute: nameAttribute,
                isCustom: isCustom,
                count: count
            },
            success: function (response) {
                if (response) {
                    $(".add_btn_row").before(response);
                }
            },
        });
    }
});

function setNewWidth(newWidth)
{
    $('.wk-row-width').width(newWidth);
    $('#wk_size_chart_container').width(newWidth);
}

function toggleHideAttributes()
{
    $("#show_attribute").addClass("hidden");
    var currentType = $("input[name=size_chart_type]:checked").val();
    if (typeof image_type !== 'undefined' && currentType == image_type) {
        $("#custom_attribute").parents(".form-group:first").addClass("hidden");
        $("#attribute_checkbox").addClass("hidden");
        $("#id_attribute_group").parents(".form-group:first").addClass("hidden");
        $(".back_btn_row").addClass("hidden");
    } else if (currentType == predefined_type) {
        $("#custom_attribute").parents(".form-group:first").addClass("hidden");
        $("#attribute_checkbox").removeClass("hidden");
        $("#id_attribute_group").parents(".form-group:first").removeClass("hidden");
        $(".back_btn_row").addClass("hidden");
    } else {
        $("#id_attribute_group").parents(".form-group:first").addClass("hidden");
        $("#attribute_checkbox").addClass("hidden");
        $("#custom_attribute").parents(".form-group:first").removeClass("hidden");
        $(".back_btn_row").removeClass("hidden");
    }
}

function toggleHideOnEdit()
{
    var currentType = $("input[name=size_chart_type]:checked").val();
    if (typeof image_type !== 'undefined' && currentType == image_type) {
        $("#custom_attribute").parents(".form-group:first").addClass("hidden");
        $("#attribute_checkbox").addClass("hidden");
        $("#id_attribute_group").parents(".form-group:first").addClass("hidden");
        $("#show_attribute").addClass("hidden");
        $(".back_btn_row").addClass("hidden");
    } else if (currentType == predefined_type) {
        $("#custom_attribute").parents(".form-group:first").addClass("hidden");
        $("#attribute_checkbox").removeClass("hidden");
        $("#id_attribute_group").parents(".form-group:first").removeClass("hidden");
        $(".back_btn_row").addClass("hidden");
        if ($(".attribute_list:checked")) {
            $("#show_attribute").removeClass("hidden");
        }
    } else {
        $("#id_attribute_group").parents(".form-group:first").addClass("hidden");
        $("#attribute_checkbox").addClass("hidden");
        $(".back_btn_row").removeClass("hidden");
    }
}

function displayCheckbox(idAttribute)
{
    var idAttributeGroup = $("#id_attribute_group option:checked").val();
    $.ajax({
        url: WkSizeChartLink,
        type: "POST",
        cache: false,
        dataType: 'json',
        data: {
            ajax: true,
            action: "displayAttributesList",
            idAttributeGroup: idAttributeGroup,
            attributeId: idAttribute
        },
        success: function (response) {
            if (response) {
                $("#show_attribute").before(response);
            }
        },
    });
}

function setSizeChartAttribute(attributeIds, isCustom = false)
{
    nameAttribute = $("#custom_attribute").val();
    $.ajax({
        url: WkSizeChartLink,
        type: "POST",
        cache: false,
        dataType: 'json',
        data: {
            ajax: true,
            action: "displayAttributes",
            attributeIds: attributeIds,
            nameAttribute: nameAttribute,
            isCustom: isCustom,
        },
        success: function (response) {
            if (response) {
                $(".wk-size-chart-lang-btn").parent().remove();
                $("#show_attribute").removeClass("hidden");
                $("#wk_attr_option").append(response);
            }
            if (typeof isCustom != 'undefined' && isCustom) {
                $("#custom_attribute").parents(".form-group:first").addClass("hidden");
            }
        },
    });
}
