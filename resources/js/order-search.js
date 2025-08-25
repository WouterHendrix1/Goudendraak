import { createApp, reactive, computed } from 'vue'

const el = document.getElementById('order-search-root')
if (el) {
  const rows = JSON.parse(el.dataset.rows || '[]')
  const cats = JSON.parse(el.dataset.cats || '[]')
  const old  = JSON.parse(el.dataset.old  || '{}')

  // aantallen & opmerkingen per originele index
  const qty = reactive([])
  const remarks = reactive([])

  rows.forEach(r => {
    qty[r.idx]     = Number(old?.[r.idx]?.aantal ?? 0) || 0
    remarks[r.idx] = old?.[r.idx]?.opmerking ?? ''
  })

  createApp({
    data: () => ({
      rows, cats,
      q: '', cat: '',
      qty, remarks,
    }),
    computed: {
      filtered() {
        const q = this.q.trim().toLowerCase()
        const cat = this.cat
        return this.rows.filter(r => {
          const okCat = !cat || r.categorie === cat
          if (!q) return okCat
          return okCat && (String(r.id).includes(q) || r.naam.toLowerCase().includes(q))
        })
      },
    },
    methods: {
      step(idx, d) {
        const v = Number(this.qty[idx]) || 0
        this.qty[idx] = Math.max(0, v + d)
      },
      money(n) {
        return Number(n || 0).toFixed(2).replace('.', ',')
      },
    },
  }).mount(el)
}
