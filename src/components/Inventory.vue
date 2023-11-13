<template>
    <k-inside>

      <k-header>
        {{ $t('lendmanagement.inventory') }}

        <k-button-group slot="right">
          <k-button icon="add"
                    variant="filled"
                    :text="$t('lendmanagement.category.add')"
                    @click="$dialog('inventory/item/create')"/>
          <k-button icon="add"
                    variant="filled"
                    :text="$t('lendmanagement.item.add')"
                    @click="$dialog('inventory/item/create')"/>
        </k-button-group>
      </k-header>

      <table class="k-inventory">

        <tr>
          <th style="text-align: center;">#</th>
          <th>{{ $t('lendmanagement.inventory.table.title') }}</th>
          <th>{{ $t('lendmanagement.inventory.table.category') }}</th>
          <th>{{ $t('lendmanagement.inventory.table.quantity') }}</th>
          <th>{{ $t('lendmanagement.inventory.table.updated_at') }}</th>
          <th>{{ $t('lendmanagement.inventory.table.created_at') }}</th>
          <th class="k-product-options"></th>
        </tr>

        <tr v-for="(item, id) in items" :key="id">
          <td id="item-id" style="text-align: center;">{{ item.id}}</td>
          <td class="name fitwidth">{{ item.title }}</td>
          <td>{{ item.category }}</td>
          <td class="fitwidth" style="text-align: center;">{{ item.quantity }}</td>
          <td>{{ item.updated_at }}</td>
          <td>{{ item.created_at }}</td>
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
.k-inventory {
  width: 100%;
  table-layout: auto;
  border-collapse: collapse;
}

.k-inventory th {
  text-align: left;
  padding: 0.5rem;
  border-bottom: 1px solid var(--color-border);
  background: var(--color-gray-100);
}

.k-inventory td {
  padding: 0.5rem;
  border: 1px solid var(--color-border);
  background: var(--color-white);
}
</style>
