<template>
    <div>
        <h3>Reports</h3>
        <p class="custom-text" @click="print_report1()">Order reports</p>
        <p class="custom-text" @click="print_report2()">Daily stocks</p>
        <p class="custom-text" @click="print_bm6()">BM-06</p>

        
        <!-- Modal -->
        <div class="modal fade" id="modal_print_cafoa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span class="fa fa-print"></span> PRINT</h5>
            </div>
            <div class="modal-body">
                <label>Year</label>
                <input type="text" v-model="year">
                <button class="btn btn-primary" @click="display_report1()" v-if="report == 1">Display</button>
                <button class="btn btn-primary" @click="display_report2()" v-if="report == 2">Display</button>
                <button class="btn btn-primary" @click="display_bm6()" v-if="report == 3">Display</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" @click="close()">Close</button>
            </div>
            </div>
        </div>
        </div>
    </div>
</template>
<style scoped>
.custom-text {
    cursor: pointer;
}
.custom-text:hover {
    text-decoration: underline;
}
</style>
<script>
export default {
    data() {
        return {
            year: "",
            report: 0
        }
    },
    methods: {
        display_report1() {
            var url =
                "http://192.168.6.23:8080/jasperserver/flow.html?pp=u%3DJamshasadid%7Cr%3DManager%7Co%3DEMEA,Sales%7Cpa1%3DSweden&" +
                "flow.html?_flowId=viewReportFlow&_flowId=viewReportFlow&ParentFolderUri=%2Freports%2Fblood_inventory%2Fblood_report1_files&reportUnit=%2Freports%2Fblood_inventory%2Fblood_report1_files%2Fblood_report1_files&standAlone=true" +
                "&decorate=no" +
                "&year="+this.year;

            window.open(
              url,
              '_blank'
            );
        },

        display_report2() {
            var url =
                "http://192.168.6.23:8080/jasperserver/flow.html?pp=u%3DJamshasadid%7Cr%3DManager%7Co%3DEMEA,Sales%7Cpa1%3DSweden&" +
                "flow.html?_flowId=viewReportFlow&_flowId=viewReportFlow&ParentFolderUri=%2Freports%2Fblood_inventory&reportUnit=%2Freports%2Fblood_inventory%2Fblood_report2_files&standAlone=true" +
                "&decorate=no" +
                "&year="+this.year;

            window.open(
              url,
              '_blank'
            );
        },

        display_bm6() {
            var url =
                "http://192.168.6.23:8080/jasperserver/flow.html?pp=u%3DJamshasadid%7Cr%3DManager%7Co%3DEMEA,Sales%7Cpa1%3DSweden&" +
                "flow.html?_flowId=viewReportFlow&_flowId=viewReportFlow&ParentFolderUri=%2Freports%2Fblood_inventory&reportUnit=%2Freports%2Fblood_inventory%2Fbm6&standAlone=true" +
                "&decorate=no" +
                "&year="+this.year;

            window.open(
              url,
              '_blank'
            );
        },

        print_report1() {
            this.report = 1
            $("#modal_print_cafoa").modal("show");
        },
        
        print_report2() {
            this.report = 2
            $("#modal_print_cafoa").modal("show");
        },
        print_bm6() {
            this.report = 3
            $("#modal_print_cafoa").modal("show");
        },

        close() {
            this.year = ""
             $("iframe").attr("src", "");
             $("#modal_print_cafoa").modal("hide");
        }
    }
}
</script>