<template>
  <k-inside>
    <k-view>
      <k-header>
        {{ $t('lendmanagement.borrowers') }}
        <k-button-group slot="left">
          <k-button
            text="New Borrower"
            icon="add"
            @click="$dialog('lendmanagement/borrowers/borrower/create')"
          />
        </k-button-group>
      </k-header>

      <table class="k-products">
        <tr>
          <th>{{ $t('lendmanagement.borrowers.table.firstname') }}</th>
          <th>{{ $t('lendmanagement.borrowers.table.lastname') }}</th>
          <th>{{ $t('lendmanagement.borrowers.table.email') }}</th>
          <th>{{ $t('lendmanagement.borrowers.table.phone') }}</th>
          <th>{{ $t('lendmanagement.borrowers.table.lastlend') }}</th>
        </tr>
        <tr v-for="(borrower, id) in borrowers" :key="id">
          <td>{{ borrower.firstname }}</td>
          <td>{{ borrower.lastname }}</td>
          <td>{{ borrower.email }}</td>
          <td>{{ borrower.phone }}</td>
          <td>{{ borrower.lastlend }}</td>
        </tr>
      </table>
    </k-view>
  </k-inside>
</template>

<script>
export default {
  props: {
    borrowers: Object
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
.k-products {
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
</style>
