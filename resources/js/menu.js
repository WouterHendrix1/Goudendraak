import { createApp, computed, reactive } from 'vue'

const COOKIE_NAME = 'fav_gerechten'
const ONE_YEAR = 60 * 60 * 24 * 365

function readFavCookie() {
  const m = document.cookie.match(new RegExp('(?:^|; )' + COOKIE_NAME + '=([^;]*)'))
  if (!m) return []
  try { return JSON.parse(decodeURIComponent(m[1])) || [] } catch { return [] }
}
function writeFavCookie(ids) {
  const v = encodeURIComponent(JSON.stringify(ids))
  document.cookie = `${COOKIE_NAME}=${v}; path=/; max-age=${ONE_YEAR}; SameSite=Lax`
}

const root = document.getElementById('menu-root')
if (root) {
  const rows = JSON.parse(root.dataset.rows || '[]')
  const cats = JSON.parse(root.dataset.cats || '[]')

  // favorieten als reactieve map: id => true
  const favMap = reactive({})
  readFavCookie().forEach(id => { favMap[id] = true })

  createApp({
    data: () => ({
      rows, cats,
      q: '', cat: '',
      sortMode: 'default', // 'fav-first-number' | 'fav-alpha-top'
      favMap,
    }),
    computed: {
      filtered() {
        const q = this.q.trim().toLowerCase()
        const cat = this.cat

        let base = this.rows.filter(r => {
          const okCat = !cat || r.categorie === cat
          if (!q) return okCat
          return okCat && (String(r.id).includes(q) || r.naam.toLowerCase().includes(q))
        })

        // sorteren
        if (this.sortMode === 'fav-first-number') {
          const favs = base.filter(r => this.isFav(r.id)).sort((a,b) => a.id - b.id)
          const rest = base.filter(r => !this.isFav(r.id)).sort((a,b) => a.id - b.id)
          return [...favs, ...rest]
        }
        if (this.sortMode === 'fav-alpha-top') {
          const favs = base
            .filter(r => this.isFav(r.id))
            .sort((a,b) => a.naam.localeCompare(b.naam, 'nl', {sensitivity:'base'}))
          const rest = base
            .filter(r => !this.isFav(r.id))
            .sort((a,b) => 0) // behoud invoervolgorde
          return [...favs, ...rest]
        }
        // standaard: op nummer
        return base.sort((a,b) => a.id - b.id)
      },
    },
    methods: {
      isFav(id) { return !!this.favMap[id] },
      toggleFav(id) {
        if (this.favMap[id]) delete this.favMap[id]
        else this.favMap[id] = true
        writeFavCookie(this.favIds())
      },
      favIds() {
        return this.rows.filter(r => this.isFav(r.id)).map(r => r.id)
      },
      money(n) {
        return Number(n || 0).toFixed(2).replace('.', ',')
      },
    },
  }).mount(root)
}
