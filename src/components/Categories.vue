<template>
  <k-inside>
    <k-header>
      {{ $t('lendmanagement.categories') }}

      <k-button-group slot="buttons">
        <k-button
          :text="$t('lendmanagement.category.add')"
          variant="filled"
          icon="add"
          @click="$dialog('lendmanagement/category/create')"
        />
      </k-button-group>
    </k-header>

    <table class="k-categories">
      <tr>
        <th class="k-categories-options" style="text-align: center;">#</th>
        <th>{{ $t('lendmanagement.categories.table.name') }}</th>
        <th>{{ $t('lendmanagement.categories.table.location') }}</th>
        <th>{{ $t('lendmanagement.categories.table.nbrObjects') }}</th>
        <th class="k-categories-options"></th>
      </tr>

      <tr v-for="(category, id) in categories" :key="id">
        <td style="text-align: center;">{{ category.id }}</td>
        <td>{{ category.name }}</td>
        <td>{{ category.location }}</td>
        <td>{{ category.totalOfObjects }}</td>
        <td class="k-categories-options">
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
.k-categories {
  width: 100%;
  table-layout: fixed;
  border-spacing: 1px;
}
.k-categories td {
  text-align: left;
  font-size: var(--text-sm);
  padding: var(--spacing-2);
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
  background: var(--color-white);
}

.k-categories th {
  text-align: left;
  font-size: var(--text-sm);
  padding: var(--spacing-2);
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
  border-bottom: 1px solid var(--color-border);
  background: var(--color-gray-100);
}

.k-categories-options {
  padding: 0 !important;
  width: 3rem;
  overflow: visible !important;
}
</style>
