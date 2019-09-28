import Metadata from './components/Metadata'

App.booting((Vue, router) => {
    Vue.component('metadata-tool', Metadata)
})