<script>
export default {
  name: 'MenuItem',
  functional: true,
  props: {
    icon: {
      type: String,
      default: '',
    },
    title: {
      type: String,
      default: '',
    },
  },
  render(h, context) {
    const { icon, title } = context.props;
    const vnodes = [];

    if (icon) {
      // vnodes.push(<svg-icon icon-class={icon}/>);
      vnodes.push(<font-awesome-icon size='lg' icon={icon} />);
      //   vnodes.push(<font-awesome-icon icon={icon} />);
    }

    if (title) {
      vnodes.push(<span style='margin-left: 0.5rem;' slot='title'>{(title)}</span>);
    }
    return vnodes;
  },
};
</script>
