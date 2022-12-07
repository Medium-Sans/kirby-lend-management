<template>
  <k-inside>
    <k-view>
      <k-header>
        {{ $t('lendmanagement.borrowers') }}
        <k-button-group slot="left">
          <k-button
            text="New Borrower"
            icon="add"
            @click="$dialog('lendmanagement/borrower/create')"
          />
        </k-button-group>
        <k-button-group slot="right">
          <k-button-link
            icon="cart"
            :link="`/lendmanagement/`"
          >
            {{ $t('lendmanagement.dashboard.view') }}
          </k-button-link>
          <k-button-link
            icon="table"
            :link="`/lendmanagement/inventory/`"
          >
            {{ $t('lendmanagement.dashboard.inventory.view') }}
          </k-button-link>
        </k-button-group>
      </k-header>

      <table class="k-products">
        <tr>
          <th>{{ $t('lendmanagement.borrowers.table.firstname') }}</th>
          <th>{{ $t('lendmanagement.borrowers.table.lastname') }}</th>
          <th>{{ $t('lendmanagement.borrowers.table.email') }}</th>
          <th>{{ $t('lendmanagement.borrowers.table.phone') }}</th>
          <th>{{ $t('lendmanagement.borrowers.table.lastlend') }}</th>
          <th class="k-product-options"></th>
        </tr>
        <tr v-for="(borrower, id) in borrowers" :key="id">
          <td>{{ borrower.firstname }}</td>
          <td>{{ borrower.lastname }}</td>
          <td>{{ borrower.email }}</td>
          <td>{{ borrower.phone }}</td>
          <td>{{ borrower.lastLendAt }}</td>
          <td class="k-product-options">
            <k-options-dropdown :options="'lendmanagement/borrower' + id" />
          </td>
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
.k-product-options {
  padding: 0 !important;
  width: 3rem;
  overflow: visible !important;
}
</style>
