<template>
  <k-inside>
    <k-view>
      <k-header>
        {{ category.title }}
        <k-button-group slot="left">
          <k-button
            text="Delete"
            icon="trash"
            @click="$dialog('/inventory/category/' + category.id + '/delete')"
          />
        </k-button-group>

        <k-button-group slot="right">
          <k-button
            icon="add"
            @click="$dialog('/inventory/item/create')"
          >
            {{ $t('lendmanagement.item.add') }}
          </k-button>
        </k-button-group>
      </k-header>

      <k-collection layout="list" :items="items" />
    </k-view>
  </k-inside>
</template>

<script>
export default {
  props: {
    category: Object,
    items: Object
  },
  methods: {
    price(price) {
      return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(price);
    },
  }
};
</script>
