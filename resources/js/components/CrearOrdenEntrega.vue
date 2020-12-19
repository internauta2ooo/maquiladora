<template>
    <div class="container">

        <p>slpslsls 2.000</p>
         <b-modal id="testme"
                        >Hello From My Modal</b-modal
                    >
           <b-button
                            size="sm"
                            @click="$bvModal.show('testme')"
                            variant="primary"
                            >Imprimir PDF orden2.0</b-button
                        >
    </div>
</template>

<script>
export default {
    methods: {
        
    },
    data() {
        return {
           
        };
    }
};
</script>
<style scoped>
#ordenes {
    border-style: solid;
    border-color: rgb(199, 199, 199);
    margin: 5px;
}
.botonesmargen {
    margin-top: -5px;
    margin-bottom: 15px;
}
</style>
