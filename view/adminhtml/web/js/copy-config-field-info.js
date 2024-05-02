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

    // Use copy to clipboard for all browsers
    function copyToClipboard(text)
    {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            return navigator.clipboard.writeText(text);
        } else {
            if (window.clipboardData && window.clipboardData.setData) {
                // IE: prevent textarea being shown while dialog is visible
                return window.clipboardData.setData("Text", text);

            } else if (document.queryCommandSupported &&
                document.queryCommandSupported("copy")) {
                let textarea = document.createElement("textarea");
                textarea.textContent = text;
                // Prevent scrolling to bottom of page in MS Edge
                textarea.style.position = "fixed";
                document.body.appendChild(textarea);
                textarea.select();
                try {
                    // Security exception may be thrown by some browsers
                    return document.execCommand("copy");
                } catch (ex) {
                    console.warn("Copy to clipboard failed.", ex);
                    return false;
                } finally {
                    document.body.removeChild(textarea);
                }
            }
        }
    }
    document.addEventListener('click', function (e) {
        let target = e.target, aTouatiCopyValueText = $t('Copy Value'),
            aTouatiCopyPathValueText = $t('Copy Hint Path');

        if (target.classList.contains('atouati-config-path-Copy') || target.classList.contains('atouati-config-value-Copy')) {
            let element = e.target, id = element.id;
            if (target.classList.contains('atouati-config-path-Copy')) {
                const path = element.dataset.path;
                copyToClipboard(path);
                if (window.ATouatiCopyValue) {
                    document.querySelector('#' + window.ATouatiCopyValue).innerHTML = aTouatiCopyValueText;
                }
                if (window.ATouatiCopyPath) {
                    document.querySelector('#' + window.ATouatiCopyPath).innerHTML = aTouatiCopyPathValueText;
                }
                element.innerHTML = $t('Hint Path Copied');
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
                copyToClipboard(value);
                if (window.ATouatiCopyPath) {
                    document.querySelector('#' + window.ATouatiCopyPath).innerHTML = aTouatiCopyPathValueText;
                }
                if (window.ATouatiCopyValue) {
                    document.querySelector('#' + window.ATouatiCopyValue).innerHTML = aTouatiCopyValueText;
                }
                element.innerHTML = $t('Value Copied');
                window.ATouatiCopyValue = id;
            }
        }
    });
});
