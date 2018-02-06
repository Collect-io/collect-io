import {Component, Vue} from 'vue-property-decorator'
import WithRender from './Header.html'

@WithRender
@Component
export default class ColllectHeader extends Vue {
  get nickname() {
    return this.$store.state.auth.nickname
  }
}
