(function () {
  'use strict';

  const page = document.getElementById('cart-page');
  if (!page || !window.cartRoutes) {
    return;
  }

  const currency = page.dataset.currency || '';
  const csrf = window.cartRoutes.csrf;

  function formatMoney(amount) {
    return Number(amount).toFixed(2) + ' ' + currency;
  }

  function request(url, method, body) {
    const options = {
      method: method,
      headers: {
        Accept: 'application/json',
        'X-CSRF-TOKEN': csrf,
        'X-Requested-With': 'XMLHttpRequest',
      },
    };

    if (body !== undefined) {
      options.headers['Content-Type'] = 'application/json';
      options.body = JSON.stringify(body);
    }

    return fetch(url, options).then(function (res) {
      return res.json().then(function (data) {
        if (!res.ok) {
          throw data;
        }
        return data;
      });
    });
  }

  function updateSummary(data) {
    const summary = data.summary || {};
    const subtotalEl = document.getElementById('cart-subtotal');
    const finalEl = document.getElementById('cart-final');
    const discountRow = document.getElementById('cart-discount-row');
    const discountEl = document.getElementById('cart-discount');
    const couponError = document.getElementById('cart-coupon-error');

    if (subtotalEl) {
      subtotalEl.textContent = formatMoney(summary.subtotal || 0);
    }
    if (finalEl) {
      finalEl.textContent = formatMoney(summary.final_amount || 0);
    }

    if (discountRow && discountEl) {
      if ((summary.discount_amount || 0) > 0) {
        discountRow.classList.remove('d-none');
        discountEl.textContent = '− ' + formatMoney(summary.discount_amount);
      } else {
        discountRow.classList.add('d-none');
      }
    }

    if (couponError) {
      if (data.coupon_ok === false && data.coupon_message) {
        couponError.textContent = data.coupon_message;
        couponError.classList.remove('d-none');
      } else {
        couponError.classList.add('d-none');
        couponError.textContent = '';
      }
    }

    if (data.lines) {
      data.lines.forEach(function (line) {
        const card = document.querySelector('.cart-line[data-key="' + line.key + '"]');
        if (!card) {
          return;
        }
        const totalEl = card.querySelector('.cart-line-total');
        const qtyInput = card.querySelector('.cart-qty-input');
        if (totalEl && line.allocation) {
          totalEl.textContent = formatMoney(line.allocation.final_amount);
        }
        if (qtyInput) {
          qtyInput.value = line.quantity;
        }
      });
    }

    const badge = document.getElementById('cart-count-badge');
    if (badge && data.cart_count !== undefined) {
      badge.textContent = data.cart_count;
      badge.classList.toggle('d-none', data.cart_count < 1);
    }
  }

  function reloadCart() {
    return request(window.location.href, 'GET').then(updateSummary);
  }

  document.querySelectorAll('.cart-qty-plus').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const key = btn.dataset.key;
      const input = document.querySelector('.cart-qty-input[data-key="' + key + '"]');
      const qty = parseInt(input.value, 10) + 1;
      request(window.cartRoutes.update + '/' + key, 'PATCH', { quantity: qty })
        .then(updateSummary)
        .catch(function () {
          reloadCart();
        });
    });
  });

  document.querySelectorAll('.cart-qty-minus').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const key = btn.dataset.key;
      const input = document.querySelector('.cart-qty-input[data-key="' + key + '"]');
      const qty = Math.max(0, parseInt(input.value, 10) - 1);
      request(window.cartRoutes.update + '/' + key, 'PATCH', { quantity: qty })
        .then(function (data) {
          if (data.cart_count === 0 || !document.querySelector('.cart-line')) {
            window.location.reload();
            return;
          }
          updateSummary(data);
        })
        .catch(function () {
          reloadCart();
        });
    });
  });

  document.querySelectorAll('.cart-remove-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const key = btn.dataset.key;
      if (!confirm('هل تريد حذف هذا العنصر من السلة؟')) {
        return;
      }
      request(window.cartRoutes.destroy + '/' + key, 'DELETE')
        .then(function () {
          window.location.reload();
        });
    });
  });

  const couponBtn = document.getElementById('cart-coupon-apply');
  if (couponBtn) {
    couponBtn.addEventListener('click', function () {
      const input = document.getElementById('cart-coupon-input');
      request(window.cartRoutes.coupon, 'POST', { coupon_code: input ? input.value : '' })
        .then(updateSummary)
        .catch(function (err) {
          updateSummary({
            coupon_ok: false,
            coupon_message: err.message || 'تعذر تطبيق الكوبون.',
            summary: {},
          });
        });
    });
  }
})();
