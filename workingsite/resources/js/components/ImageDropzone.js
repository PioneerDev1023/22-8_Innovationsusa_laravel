import * as React from "react";
import ReactDOM from "react-dom";
import { Dropzone, FileItem } from "@dropzone-ui/react";

function ImageDropzone() {

    const [files, setFiles] = React.useState([]);


    const updateFiles = (incommingFiles) => {
        setFiles(incommingFiles);

        let dropzone = document.getElementById('files');

        let dataTransfer = new DataTransfer();

        let incomeFiles = incommingFiles.map(value => value.file);
        
        Array.prototype.forEach.call(incomeFiles, file => {

            dataTransfer.items.add(file);
        });  

        var filesToBeAdded = dataTransfer.files;

        dropzone.files = filesToBeAdded;
        console.log(dropzone.files);

    };
    return (
        <Dropzone onChange={updateFiles} value={files} accept={"image/*"} maxFileSize={524288000}>
            {files.map((file, index) => (
                <FileItem key={"file" + index} {...file} preview />
            ))}
        </Dropzone>
    );
}
if (document.getElementById('user-image-input')) {
    ReactDOM.render(<ImageDropzone key={1} />, document.getElementById('user-image-input'));
}