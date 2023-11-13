<template>
  <k-inside>
    <k-header>
      {{ $t('lendmanagement.categories') }}

      <k-button-group slot="left">
        <k-button
          :text="$t('lendmanagement.category.add')"
          variant="filled"
          icon="add"
          @click="$dialog('lendmanagement/category/create')"
        />
      </k-button-group>
    </k-header>

    <table class="k-products">
      <tr>
        <th class="k-product-options" style="text-align: center;">#</th>
        <th>{{ $t('lendmanagement.categories.table.name') }}</th>
        <th>{{ $t('lendmanagement.categories.table.location') }}</th>
        <th>{{ $t('lendmanagement.categories.table.nbrObjects') }}</th>
        <th class="k-product-options"></th>
      </tr>
      <tr v-for="(category, id) in categories" :key="id">
        <td style="text-align: center;">{{ category.id }}</td>
        <td>{{ category.name }}</td>
        <td>{{ category.location }}</td>
        <td>{{ category.nbrObjects }}</td>
        <td class="k-product-options">
          <k-options-dropdown :options="'lendmanagement/category/' + category.id"/>
        </td>
      </tr>
    </table>
  </k-inside>
</template>

<script>
export default {
  props: {
    categories: Array
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
