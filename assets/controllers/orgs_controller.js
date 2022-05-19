import SurvosDataTable_controller from "./SurvosDataTable_controller";

export default class extends SurvosDataTable_controller {

    static targets = ["table"]

    connect() {
        console.log("Connecting to " + this.identifier + ", will call " + this.apiCallValue);
        super.connect();
    }

    columns() {
        return [
            this.c({propertyName: 'id'}),
            this.c({propertyName: 'name'}),
            this.c({propertyName: 'slug'}),
            // this.c({
            //     propertyName: 'youtubeId',
            //     render: (data, type, row, meta) => {
            //         let str = ''
            //              str += `                <a href="${row.youtubeUrl}" target="_blank">
            //              <img src="${row.thumbnail.url}"  />
            //          </a>`;
            //         return str;
            //     }
            // }),
            this.c({propertyName: 'marking'}),
            // this.c({propertyName: 'enabledTransitions', route: 'project_transition'}),
            // this.c({propertyName: 'title', route: 'video_show'}),
            // this.transitions({prefix: 'project_'}),
            this.actions({prefix: 'org_'}),
        ];
    }


}

