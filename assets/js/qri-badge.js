/* ------------------------------------------------------------------
 * qri-badge.js - menggambar QR Code ke <canvas> + tombol unduh PNG.
 * Memakai pustaka lokal assets/js/qrcode-generator.js (qrcode-generator
 * oleh Kazuhiko Arase) supaya tetap jalan tanpa koneksi internet.
 * ------------------------------------------------------------------ */
(function () {
	'use strict';

	function pesanGagal(el, teks) {
		var box = el.parentNode;
		if (box) {
			box.innerHTML = '<div class="qr-gagal">' + teks + '</div>';
		}
	}

	/**
	 * Gambar QR ke canvas.
	 * @param {HTMLCanvasElement} canvas
	 * @param {string} text  isi QR (URL halaman pegawai)
	 * @param {number} size  ukuran sisi dalam pixel CSS
	 * @param {number} quiet jumlah modul putih di sekeliling (margin)
	 */
	function paint(canvas, text, size, quiet) {
		var qr = qrcode(0, 'M');
		qr.addData(text);
		qr.make();

		var n = qr.getModuleCount();
		quiet = (typeof quiet === 'number') ? quiet : 2;
		var total = n + quiet * 2;
		var px = Math.max(1, Math.floor((size || 220) / total));
		var side = px * total;

		canvas.width = side;
		canvas.height = side;
		canvas.style.width = (size || 220) + 'px';
		canvas.style.height = (size || 220) + 'px';

		var ctx = canvas.getContext('2d');
		ctx.fillStyle = '#ffffff';
		ctx.fillRect(0, 0, side, side);
		ctx.fillStyle = '#0f172a';
		for (var r = 0; r < n; r++) {
			for (var c = 0; c < n; c++) {
				if (qr.isDark(r, c)) {
					ctx.fillRect((c + quiet) * px, (r + quiet) * px, px, px);
				}
			}
		}
		canvas.setAttribute('data-siap', '1');
		return canvas;
	}

	function renderSemua() {
		var kanvas = document.querySelectorAll('canvas[data-qr]');
		if (!kanvas.length) {
			return;
		}
		if (typeof window.qrcode !== 'function') {
			for (var i = 0; i < kanvas.length; i++) {
				pesanGagal(kanvas[i], 'Pustaka QR gagal dimuat. Periksa berkas assets/js/qrcode-generator.js.');
			}
			return;
		}
		for (var j = 0; j < kanvas.length; j++) {
			try {
				paint(
					kanvas[j],
					kanvas[j].getAttribute('data-qr'),
					parseInt(kanvas[j].getAttribute('data-ukuran'), 10) || 220,
					2
				);
			} catch (e) {
				pesanGagal(kanvas[j], 'QR gagal dibuat.');
			}
		}
	}

	function unduh(btn) {
		var cv = document.getElementById(btn.getAttribute('data-kanvas'));
		if (!cv || cv.getAttribute('data-siap') !== '1') {
			alert('QR belum siap digambar. Tunggu sebentar lalu coba lagi.');
			return;
		}
		var a = document.createElement('a');
		a.download = btn.getAttribute('data-nama') || 'qr-pegawai.png';
		a.href = cv.toDataURL('image/png');
		document.body.appendChild(a);
		a.click();
		document.body.removeChild(a);
	}

	document.addEventListener('DOMContentLoaded', function () {
		renderSemua();
		var tombol = document.querySelectorAll('[data-qr-unduh]');
		for (var k = 0; k < tombol.length; k++) {
			tombol[k].addEventListener('click', function (ev) {
				ev.preventDefault();
				unduh(this);
			});
		}
	});
})();
