/**
 * Copyright (c) 2023
 * MIT License
 * Module AnassTouatiCoder_InstantConfigurationCopy
 * Author Anass TOUATI anass1touati@gmail.com
 */
require([
    'jquery',
    'mage/translate'
], function ($, $t) {
    document.addEventListener('click', function (e) {
        let target = e.target, aTouatiCopyValueText = $t('Copy Value'),
            aTouatiCopyPathValueText = $t('Copy Hint Path');

        if (target.classList.contains('atouati-config-path-Copy') || target.classList.contains('atouati-config-value-Copy')) {
            let element = e.target, id = element.id;
            if (target.classList.contains('atouati-config-path-Copy')) {
                const path = element.dataset.path;
                navigator.clipboard.writeText(path);
                element.innerHTML = $t('Hint Path Copied');
                if (window.ATouatiCopyPath !== id) {
                    if (window.ATouatiCopyValue) {
                        document.querySelector('#' + window.ATouatiCopyValue).innerHTML = aTouatiCopyValueText;
                    }
                    if (window.ATouatiCopyPath) {
                        document.querySelector('#' + window.ATouatiCopyPath).innerHTML = aTouatiCopyPathValueText;
                    }
                }
                window.ATouatiCopyPath = id;
            } else if (target.classList.contains('atouati-config-value-Copy')) {
                let fieldId = element.dataset.fieldId, value = false;
                if (element.dataset.fieldValueType === 'multiselect') {
                    let selectedValues = [];
                    let multiSelectElement = document.getElementById(fieldId);
                    for (let i = 0; i < multiSelectElement.options.length; i++) {
                        if (multiSelectElement.options[i].selected) {
                            selectedValues.push(multiSelectElement.options[i].value);
                        }
                    }
                    value = selectedValues.join(', ');
                } else {
                    let SelectedElement = document.getElementById(fieldId);
                    if (SelectedElement === null) {
                        // field does not have value, its just block
                        return;
                    }
                    if (SelectedElement.tagName === "TABLE") {
                        // Do not support grid field
                        return;
                    }
                    value = SelectedElement ? SelectedElement.value : false;
                }
                if (value === false) {
                    return;
                }

                navigator.clipboard.writeText(value);
                element.innerHTML = $t('Value Copied');
                if (window.ATouatiCopyValue !== id) {
                    if (window.ATouatiCopyPath) {
                        document.querySelector('#' + window.ATouatiCopyPath).innerHTML = aTouatiCopyPathValueText;
                    }
                    if (window.ATouatiCopyValue) {
                        document.querySelector('#' + window.ATouatiCopyValue).innerHTML = aTouatiCopyValueText;
                    }
                }
                window.ATouatiCopyValue = id;
            }
        }
    });
});
