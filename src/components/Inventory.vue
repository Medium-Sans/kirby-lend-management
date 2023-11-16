<template>
    <k-inside>

      <k-header>
        {{ $t('lendmanagement.inventory') }}

        <k-button-group slot="buttons">
          <k-button icon="add"
                    variant="filled"
                    :text="$t('lendmanagement.category.add')"
                    @click="$dialog('/lendmanagement/category/create')"/>
          <k-button icon="add"
                    variant="filled"
                    :text="$t('lendmanagement.item.add')"
                    @click="$dialog('/lendmanagement/item/create')"/>
        </k-button-group>
      </k-header>

      <table class="k-inventory">

        <tr>
          <th style="text-align: center;">#</th>
          <th>{{ $t('lendmanagement.inventory.table.title') }}</th>
          <th>{{ $t('lendmanagement.inventory.table.category') }}</th>
          <th style="text-align: center;">{{ $t('lendmanagement.inventory.table.quantity') }}</th>
          <th style="text-align: center;">{{ $t('lendmanagement.inventory.table.updatedAt') }}</th>
          <th style="text-align: center;">{{ $t('lendmanagement.inventory.table.createdAt') }}</th>
          <th class="k-product-options"></th>
        </tr>

        <tr v-for="(item, id) in items" :key="id">
          <td id="item-id" style="text-align: center;">{{ item.id }}</td>
          <td class="name fitwidth">
            <a :href="'lendmanagement/item/' + item.id">{{ item.name }}</a>
          </td>
          <td>{{ item.category }}</td>
          <td class="fitwidth" style="text-align: center;">{{ item.quantity }}</td>
          <td style="text-align: center;">{{ formatDate(item.updated_at) }}</td>
          <td style="text-align: center;">{{ formatDate(item.created_at) }}</td>
          <td class="k-product-options">
            <k-options-dropdown :options="'lendmanagement/item/' + item.id"/>
          </td>
        </tr>

      </table>
  </k-inside>
</template>

<script>
export default {
  props: {
    items: Array,
    lends: Array,
    categories: Array,
    locale: String,
  },
  methods: {
    formatDate(dateString) {
      if(!dateString) return '-';
      const date = new Date(dateString);
      // Then specify how you want your dates to be formatted
      return new Intl.DateTimeFormat(this.locale, {dateStyle: 'short', timeStyle: 'short',}).format(date);
    }
  }
};
</script>

<style>
.k-inventory {
  width: 100%;
  table-layout: auto;
  border-spacing: 1px;
}
.k-inventory td {
  text-align: left;
  font-size: var(--text-sm);
  padding: var(--spacing-2);
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
  background: var(--color-white);
}
.k-inventory th {
  text-align: left;
  font-size: var(--text-sm);
  padding: var(--spacing-2);
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
  border-bottom: 1px solid var(--color-border);
  background: var(--color-gray-100);
}
</style>
