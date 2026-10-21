/*
 * Master EP — pencarian dropdown Standar (227 data).
 *
 * Pakai select native + input cari, tanpa library tambahan.
 * Opsi yang tidak cocok disembunyikan dengan cara dibangun ulang (detach/append),
 * bukan memakai atribut hidden, supaya aman di semua browser.
 *
 * Cara pakai:
 *   MasterEp.initStandarPicker({ input: '#cari_standar', select: '#pilih_standar', info: '#info_standar' });
 */
(function (global) {
    'use strict';

    function toArray(nodeList) {
        return Array.prototype.slice.call(nodeList);
    }

    function initStandarPicker(opts) {
        opts = opts || {};

        var input = opts.input ? document.querySelector(opts.input) : null;
        var select = opts.select ? document.querySelector(opts.select) : null;
        var info = opts.info ? document.querySelector(opts.info) : null;

        if (!input || !select) {
            return;
        }

        // Kumpulkan struktur awal: grup (BAB) + opsi di dalamnya.
        var groups = [];
        var placeholder = null;

        toArray(select.children).forEach(function (child) {
            if (child.tagName === 'OPTION' && child.value === '') {
                placeholder = child;
                return;
            }
            if (child.tagName === 'OPTGROUP') {
                groups.push({
                    label: child.getAttribute('label') || '',
                    options: toArray(child.options)
                });
                return;
            }
            if (child.tagName === 'OPTION') {
                groups.push({ label: '', options: [child] });
            }
        });

        var total = 0;
        groups.forEach(function (g) {
            total += g.options.length;
        });

        var selected = select.value;

        function teksCari(option) {
            return ((option.textContent || '') + ' ' + (option.getAttribute('data-search') || '')).toLowerCase();
        }

        function apply() {
            var q = input.value.trim().toLowerCase();
            var visible = 0;

            select.innerHTML = '';
            if (placeholder) {
                select.appendChild(placeholder);
            }

            groups.forEach(function (g) {
                var cocok = g.options.filter(function (option) {
                    // Opsi yang sedang terpilih tetap selalu ditampilkan,
                    // supaya nilainya tidak hilang saat user mengetik filter.
                    if (selected !== '' && option.value === selected) {
                        return true;
                    }
                    return q === '' || teksCari(option).indexOf(q) !== -1;
                });

                if (cocok.length === 0) {
                    return;
                }

                visible += cocok.length;

                if (g.label === '') {
                    cocok.forEach(function (option) {
                        select.appendChild(option);
                    });
                    return;
                }

                var group = document.createElement('optgroup');
                group.label = g.label;
                cocok.forEach(function (option) {
                    group.appendChild(option);
                });
                select.appendChild(group);
            });

            if (selected !== '' && select.value !== selected) {
                select.value = selected;
            }

            if (info) {
                if (q === '') {
                    info.textContent = total + ' standar tersedia - ketik untuk mencari';
                } else {
                    info.textContent = visible + ' dari ' + total + ' standar cocok "' + q + '"';
                }
            }
        }

        input.addEventListener('input', apply);

        // Enter di kotak pencarian tidak boleh submits form.
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });

        apply();
    }

    global.MasterEp = {
        initStandarPicker: initStandarPicker
    };
})(window);
