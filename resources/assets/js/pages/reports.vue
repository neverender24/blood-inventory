<template>
    <div>
        <h3>Reports</h3>
        <p class="custom-text" @click="print_report1()">Order reports</p>
        <p class="custom-text" @click="print_report2()">Daily stocks</p>

        
        <!-- Modal -->
        <div class="modal fade" id="modal_print_cafoa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span class="fa fa-print"></span> PRINT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src=""></iframe> 
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
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

        }
    },
    methods: {
        print_report1() {
            // var url = "http://192.168.6.23:8080/jasperserver/flow.html?pp=u%3DJamshasadid%7Cr%3DManager%7Co%3DEMEA,Sales%7Cpa1%3DSweden&_flowId=viewReportFlow&_flowId=viewReportFlow&ParentFolderUri=%2Freports&reportUnit=%2Freports%2Fcafoa&standAlone=tru&decorate=no&cafoa_id="+cafoa_id
            var url =
                "http://192.168.6.23:8080/jasperserver/flow.html?pp=u%3DJamshasadid%7Cr%3DManager%7Co%3DEMEA,Sales%7Cpa1%3DSweden&" +
                "flow.html?_flowId=viewReportFlow&_flowId=viewReportFlow&ParentFolderUri=%2Freports%2Fblood_inventory%2Fblood_report1_files&reportUnit=%2Freports%2Fblood_inventory%2Fblood_report1_files%2Fblood_report1_files&standAlone=true" +
                "&decorate=no";

            $("iframe").attr("src", url);
            $("#modal_print_cafoa").modal("show");

            //uncomment for testing
            // axios.get('/print_cafoa_get?cafoa_id=75').then( response => {})
        },
        print_report2() {
            // var url = "http://192.168.6.23:8080/jasperserver/flow.html?pp=u%3DJamshasadid%7Cr%3DManager%7Co%3DEMEA,Sales%7Cpa1%3DSweden&_flowId=viewReportFlow&_flowId=viewReportFlow&ParentFolderUri=%2Freports&reportUnit=%2Freports%2Fcafoa&standAlone=tru&decorate=no&cafoa_id="+cafoa_id
            var url =
                "http://192.168.6.23:8080/jasperserver/flow.html?pp=u%3DJamshasadid%7Cr%3DManager%7Co%3DEMEA,Sales%7Cpa1%3DSweden&" +
                "flow.html?_flowId=viewReportFlow&_flowId=viewReportFlow&ParentFolderUri=%2Freports%2Fblood_inventory&reportUnit=%2Freports%2Fblood_inventory%2Fblood_report2_files&standAlone=true" +
                "&decorate=no";

            $("iframe").attr("src", url);
            $("#modal_print_cafoa").modal("show");

            //uncomment for testing
            // axios.get('/print_cafoa_get?cafoa_id=75').then( response => {})
        }
    }
}
</script>