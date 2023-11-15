<template>
  <k-inside>

    <k-header>
      {{ $t('lendmanagement.lends') }}

      <k-button-group slot="buttons">
        <k-button variant="filled"
                  icon="add"
                  :link="`/lendmanagement/lend-add`">{{ $t('lendmanagement.lend.add') }}
        </k-button>
      </k-button-group>
    </k-header>

    <header class="k-section-header">
      <k-headline>{{ $t('lendmanagement.dashboard.status') }}</k-headline>
    </header>

    <k-stats :reports="stats">
    </k-stats>

    <k-grid gutter="large">
      <k-column width="1/2">
        <header class="k-section-header">
          <k-headline>{{ $t('lendmanagement.lend.inProgress') }}</k-headline>
        </header>
        <k-collection layout="list" :items="lends"/>
      </k-column>

      <k-column width="1/2">
        <header class="k-section-header">
          <k-headline>{{ $t('lendmanagement.lend.late') }}</k-headline>
        </header>
        <k-collection layout="list" :items="late_lends"/>
      </k-column>

      <k-column width="1/1">
        <header class="k-section-header">
          <k-headline>{{ $t('lendmanagement.lend.returned') }}</k-headline>
        </header>
        <k-collection layout="list" :items="returned_lends"/>
      </k-column>
    </k-grid>
  </k-inside>
</template>

<script>
export default {
  props: {
    items: Object,
    stats: Array,
    lends: Array,
    late_lends: Array,
    returned_lends: Array,
    categories: Array
  },
  methods: {
    // format the price in EURO
    price(price) {
      return new Intl.NumberFormat('de-DE', {style: 'currency', currency: 'EUR'}).format(price);
    }
  }
};
</script>

<style>
.k-stats {
  margin-bottom: var(--spacing-10);
}
</style>
