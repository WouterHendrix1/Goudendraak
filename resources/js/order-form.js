import { createApp } from 'vue'

const MAX_KLANTEN = 8

// Mount Vue op de klanten-sectie
const root = document.getElementById('klanten-root')
if (root) {
  const oldData = JSON.parse(root.dataset.old || '[]')
  const initialTafel = root.dataset.tafel || ''

  createApp({
    data() {
      return {
        max: MAX_KLANTEN,
        visible: Boolean(initialTafel),
        klanten: Array.isArray(oldData) && oldData.length
          ? oldData.map(k => ({
              geboortedatum: k?.geboortedatum ?? '',
              deluxe_menu: (k?.deluxe_menu ?? '0').toString()
            }))
          : [],
      }
    },
    methods: {
      addKlant() {
        if (this.klanten.length >= this.max) return
        this.klanten.push({ geboortedatum: '', deluxe_menu: '0' })
      },
      removeKlant(i) {
        this.klanten.splice(i, 1)
      },
      onTafelChange(val) {
        this.visible = !!val
        if (!this.visible) {
          this.klanten = []
        } else if (this.klanten.length === 0) {
          this.addKlant()
        }
      }
    },
    mounted() {
      const select = document.getElementById('tafel_id')
      if (select) {
        // init op basis van huidige waarde
        this.onTafelChange(select.value)
        // live bijhouden
        select.addEventListener('change', e => this.onTafelChange(e.target.value))
      }
      // als er old() data was, sectie tonen
      if (this.klanten.length > 0) this.visible = true
    }
  }).mount(root)
}

window.stepQty = function (idx, delta) {
  const input = document.getElementById('qty_' + idx)
  if (!input) return
  const current = parseInt(input.value || '0', 10) || 0
  input.value = Math.max(0, current + delta)
}
