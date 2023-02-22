<template>
  <k-inside>
    <k-view>

      <k-header>
        {{ $t('view.lendmanagement.dashboard') }}

        <k-button-group slot="left">
          <k-button-link
            icon="add"
            :link="`/lendmanagement/loan-add`"
          >
            {{ $t('lendmanagement.loan.add') }}
          </k-button-link>

          <k-button icon="undo"
                    :text="$t('lendmanagement.loan.notify')"
                    @click="$dialog('lendmanagement/loans/notifyexpired')" />
        </k-button-group>

        <k-button-group slot="right">
          <k-button-link
            icon="users"
            :link="`/lendmanagement/borrowers/`"
          >
            {{ $t('lendmanagement.borrowers.view') }}
          </k-button-link>

          <k-button-link
            icon="table"
            :link="`/lendmanagement/inventory/`"
          >
            {{ $t('lendmanagement.dashboard.inventory.view') }}
          </k-button-link>
        </k-button-group>
      </k-header>

      <header class="k-section-header">
        <k-headline>{{ $t('lendmanagement.dashboard.status') }}</k-headline>
      </header>

      <k-stats :reports="stats" size="small">
      </k-stats>

      <k-grid gutter="large">
        <k-column width="1/2">
          <header class="k-section-header">
            <k-headline>{{ $t('lendmanagement.loan') }}</k-headline>
          </header>
          <k-collection layout="list" :items="loans" />
        </k-column>

        <k-column width="1/2">
          <header class="k-section-header">
            <k-headline>{{ $t('lendmanagement.loan.returned') }}</k-headline>
          </header>
          <k-collection layout="list" :items="loans_history" />
        </k-column>
      </k-grid>

    </k-view>
  </k-inside>
</template>

<script>
export default {
  props: {
    items: Object,
    stats: Array,
    loans: Array,
    loans_history: Array,
    categories: Array
  },
  methods: {
    // format the price in EURO
    price(price) {
      return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(price);
    }
  }
};
</script>

<style>
.k-section-header {
  margin-top: var(--spacing-8);
  margin-bottom: var(--spacing-2);
}

.k-workshop {
  width: 100%;
  table-layout: fixed;
  border-spacing: 1px;
}
.k-products td,
.k-products th {
  text-align: left;
  font-size: var(--text-sm);
  padding: var(--spacing-2);
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
  background: var(--color-white);
}
.k-item-category-id {
  width: 8rem;
}
.k-product-price {
  width: 5rem;
  font-variant-numeric: tabular-nums;
  text-align: right !important;
}
.k-product-options {
  padding: 0 !important;
  width: 3rem;
  overflow: visible !important;
}
</style>
