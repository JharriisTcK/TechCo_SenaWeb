/*jshint esversion: 6 */

/*
Solicitudes en POST de la clase
En las opciones objOptionsIn, esta el paramtero valuesSend, el cual se enviara en todas las solicitudes que ejecute la clase
Todos tiene el parametro KeyJX, y todas esperan recibir un objeto con la RespuestaBool, si es verdadero o falso el resultado y RespuestaError, indicando el error
[KeyJX, '?']
valuesSend:[[?, ?], [?, ?]]
@{
    RespuestaBool: Bool,
    RespuestaError: Text
}
//----Obtener contenido
[TextareaRichNK, ContenidoGet]
@{
    valueContenido: text,
    valueImagenes: []
    valueVideos: []
}


[Contenido, '?']
//----Guardar contenido
[TextareaRichNK, ContenidoSet]
[Contenido, '?']

//----Obtener imagenes
[TextareaRichNK, ImagenesGet]
@{
    valueImagenes: [
        id_image: 0 
        Caption: ""
        Src[HT]: ""
    ]
}

//----Subir imagen
[TextareaRichNK, ImagenSubir]
[Caption, ?]
[Imagen, ?:FILE]

//----Eliminar imagen
[TextareaRichNK, ImagenEliminar]
[ImagenID, ?]

//----Obtener videos
[TextareaRichNK, VideosGet]
@{
    valueVideos: []
}

//----Youtube Video Enviar
[TextareaRichNK, VideoYoutubeSubir]
[VideoID, '?']
[Titulo, '?']
[VideoTipo, Youtube]

//----Eliminar Video
[TextareaRichNK, VideoEliminar]
[VideoID, '?']

*/

class TextareaRichNK {

    constructor(nodeIn, frameDir, urlAction, KeyJX, dirRaiz, objOptionsIn) {
        "use strict";
        var _this = this;
        this.nodeObj=nodeIn||document.createElement("div");
        this.nodeObj.innerHTML="";
        this.nodeObj.className = "TextareaRichNK";

        this.dirRaiz=dirRaiz;       
        this.urlAction=urlAction;
        this.KeyJX=KeyJX; 
        this.FrameDir=frameDir;
        
        //----------------
        objOptionsIn = objOptionsIn || {};
        this.objOptions = {
            Titulo: objOptionsIn.Titulo||"TextareaRich NeoKiri",
            valuesSend: objOptionsIn.valuesSend || [],
            valuesIn: objOptionsIn.valuesIn,
            frameRaiz: objOptionsIn.frameRaiz||"",
            maxWidth: objOptionsIn.maxWidth,
            maxHeight: objOptionsIn.maxHeight
        };
        //----------------
        this.FrameLoaded=false;
        this.Command=null;
        this.CommandBody=null;
        //----------------
        this.valueContenido="";
        this.valueImagenes=[];
        this.valueVideos=[];
        //----------------
        this.FileImagenes_LoadValues=[];//Archivos que se cargan cuando se seleccionan las imagenes
        this.FileImagenes_CanvasCargados=0;//Archivos que se cargan cuando se seleccionan las imagenes
        this.FileImagenes_Canvas=[];
        this.FileImagenes_Listos=[];
        //----------------
        this.nodeHeader=document.createElement("div");
        this.nodeHeader.className = "RichHeader";
        this.nodeObj.appendChild(this.nodeHeader);
        this.nodeMenu=document.createElement("ul");
        this.nodeMenu.className = "RichMenu";
        this.nodeObj.appendChild(this.nodeMenu);
        this.nodeImagenes=document.createElement("div");
        this.nodeObj.appendChild(this.nodeImagenes);
        this.nodeImagenes.className = "RichImagenes";
        this.nodeVideos=document.createElement("div");
        this.nodeObj.appendChild(this.nodeVideos);
        this.nodeVideos.className = "RichVideos";
        this.nodeFrame=document.createElement("iframe");
        this.nodeObj.appendChild(this.nodeFrame);
        this.nodeFrame.src = this.FrameDir;
        this.nodeFrame.className = "RichFrame";
        this.nodeTextarea = document.createElement("textarea");
        this.nodeObj.appendChild(this.nodeTextarea);
        this.nodeTextarea.className = "RichTextarea";
        this.nodeFooter=document.createElement("div");
        this.nodeFooter.className="RichFooter";
        this.nodeObj.appendChild(this.nodeFooter);
        //-------------------------------------------------
        this.nodeHeader.onclick = function () {
            console.clear();
            console.log(_this);
        };
        //----------------
        this.HeaderSet();
        //-------------------------------------------------
        this.nodeFrame.onload = function () {
            _this.FrameLoad();
            //----------------
            if(_this.objOptions.valuesIn) {
                _this.valueContenido=_this.objOptions.valuesIn.valueContenido;
                _this.valueImagenes=_this.objOptions.valuesIn.valueImagenes;
                _this.valueVideos=_this.objOptions.valuesIn.valueVideos;
                _this.ConfigAll();
            } else {
                _this.jxContenidoGet();
            }
        }; //fin frame onload
    }

    ConfigAll() {
        if(!this.FrameLoaded) {
            return false;
        }
        this.MenuSet();
        this.FooterSet();
        this.ContenidoSet();
        this.ImagenesSet();
        this.VideosSetBox();
    }

    FrameLoad() {
        var _this = this;
        this.FrameLoaded = true;
        //----------------------------------------------
        var command = this.nodeFrame.contentWindow.document;
        this.Command = command;
        command.designMode = "on";
        command.documentElement.setAttribute("spellcheck", "true");
        command.documentElement.setAttribute("lang", "es-CO");
        var bodyCommand = command.body;
        this.CommandBody = bodyCommand;
        //---------------
        command.execCommand("insertBrOnReturn", true, true);
        command.execCommand("enableObjectResizing", false, false);
        //----------------------------------------------------------------
        bodyCommand.onblur = function () {
            _this.SaveFrame();
        };
        this.nodeTextarea.onchange = function () {
            _this.SaveTextarea();
        };
    }
    //-------------------------------------------------------------------------------------------------------------------------------
    HeaderSet() {
        this.nodeHeader.innerHTML="";
        var nnTitulo = document.createElement("h2");
        nnTitulo.innerHTML=this.objOptions.Titulo;
        nnTitulo.className="RichTitulo";
        this.nodeHeader.appendChild(nnTitulo);
    }

    //-----------------------------------------------------------------------------------


    MenuSet() {
        var _this = this;
        this.nodeMenu.innerHTML="";
        var save = function () {
            _this.SaveFrame();
            _this.nodeFrame.focus();
        };
        var richUndo = document.createElement("li");
        this.nodeMenu.appendChild(richUndo);
        var richUndoSP = document.createElement("span");
        richUndo.appendChild(richUndoSP);
        richUndoSP.innerHTML = "Deshacer";
        richUndoSP.onclick = function () {
            _this.Command.execCommand("undo", false, null);
            save();
        };
        var richRedo = document.createElement("li");
        this.nodeMenu.appendChild(richRedo);
        var richRedoSP = document.createElement("span");
        richRedo.appendChild(richRedoSP);
        richRedoSP.innerHTML = "Rehacer";
        richRedoSP.onclick = function () {
            _this.Command.execCommand("redo", false, null);
            save();
        };
        //-------------
        var richBold = document.createElement("li");
        this.nodeMenu.appendChild(richBold);
        var richBoldSP = document.createElement("span");
        richBold.appendChild(richBoldSP);
        richBoldSP.innerHTML = "N";
        richBoldSP.onclick = function () {
            _this.Command.execCommand("bold", false, null);
            save();
        };
        //--------
        var richItalic = document.createElement("li");
        this.nodeMenu.appendChild(richItalic);
        var richItalicSP = document.createElement("span");
        richItalic.appendChild(richItalicSP);
        richItalicSP.innerHTML = "K";
        richItalicSP.onclick = function () {
            _this.Command.execCommand("italic", false, null);
            save();
        };
        //--------
        var richUnderline = document.createElement("li");
        this.nodeMenu.appendChild(richUnderline);
        var richUnderlineSP = document.createElement("span");
        richUnderline.appendChild(richUnderlineSP);
        richUnderlineSP.innerHTML = "S";
        richUnderlineSP.onclick = function () {
            _this.Command.execCommand("underline", false, null);
            save();
        };
        //-----------------
        var richHeadingBox = document.createElement("li");
        this.nodeMenu.appendChild(richHeadingBox);
        var richHeadingBoxSP = document.createElement("span");
        richHeadingBox.appendChild(richHeadingBoxSP);
        richHeadingBoxSP.innerHTML = "Titulacion";
        var richHeading = document.createElement("ul");
        richHeadingBox.appendChild(richHeading);
        var richHeading1 = document.createElement("li");
        richHeading1.innerHTML = "Cabecera1";
        richHeading.appendChild(richHeading1);
        richHeading1.onclick = function () {
            _this.Command.execCommand("formatBlock", false, "h1");
            save();
        };
        var richHeading2 = document.createElement("li");
        richHeading2.innerHTML = "Cabecera2";
        richHeading.appendChild(richHeading2);
        richHeading2.onclick = function () {
            _this.Command.execCommand("formatBlock", false, "h2");
            save();
        };
        var richHeading3 = document.createElement("li");
        richHeading3.innerHTML = "Cabecera3";
        richHeading.appendChild(richHeading3);
        richHeading3.onclick = function () {
            _this.Command.execCommand("formatBlock", false, "h3");
            save();
        };
        var richHeading4 = document.createElement("li");
        richHeading4.innerHTML = "Cabecera4";
        richHeading.appendChild(richHeading4);
        richHeading4.onclick = function () {
            _this.Command.execCommand("formatBlock", false, "h4");
            save();
        };
        //----------------
        var richFormatIsParagraphFN = function () {
            _this.Command.execCommand("formatBlock", false, "p");
            save();
        };
        var richFormatBlockBox = document.createElement("li");
        this.nodeMenu.appendChild(richFormatBlockBox);
        var richFormatBlockBoxSP = document.createElement("span");
        richFormatBlockBoxSP.innerHTML = "Parrafo";
        richFormatBlockBoxSP.onclick = richFormatIsParagraphFN;
        richFormatBlockBox.appendChild(richFormatBlockBoxSP);
        var richFormatBlock = document.createElement("ul");
        richFormatBlockBox.appendChild(richFormatBlock);
        var richFormatIsParagraph = document.createElement("li");
        richFormatIsParagraph.innerHTML = "Normal";
        richFormatBlock.appendChild(richFormatIsParagraph);
        richFormatIsParagraph.onclick = richFormatIsParagraphFN;
        //------------------
        var richImageBox = document.createElement("li");
        this.nodeMenu.appendChild(richImageBox);
        var richImageSP = document.createElement("span");
        richImageBox.appendChild(richImageSP);
        richImageSP.innerHTML = "Imagen";
        richImageSP.onclick = function () {
            _this.nodeImagenes.classList.toggle("activo");
            _this.nodeVideos.classList.remove("activo");
            _this.nodeMenu.focus();
        };
        //--------------------------
        var richVideoBox = document.createElement("li");
        this.nodeMenu.appendChild(richVideoBox);
        var richVideoBoxSP = document.createElement("span");
        richVideoBox.appendChild(richVideoBoxSP);
        richVideoBoxSP.innerHTML = "Video";
        richVideoBoxSP.onclick = function () {
            _this.nodeVideos.classList.toggle("activo");
            _this.nodeImagenes.classList.remove("activo");
            _this.nodeMenu.focus();
        };
        //---------------------------------
        var richLinkBox = document.createElement("li");
        this.nodeMenu.appendChild(richLinkBox);
        var richLinkSP = document.createElement("span");
        richLinkSP.innerHTML = "Link";
        richLinkBox.appendChild(richLinkSP);
        var richLinkBoxTwo = document.createElement("div");
        richLinkBoxTwo.className = "MenuLinkBox";
        richLinkBoxTwo.style.display = "none";
        richLinkBox.appendChild(richLinkBoxTwo);
        //---------------------
        var richLinkBoxTwoLabel = document.createElement("label");
        richLinkBoxTwo.appendChild(richLinkBoxTwoLabel);
        var richLinkBoxTwoB = document.createElement("b");
        richLinkBoxTwoB.innerHTML = "URi";
        richLinkBoxTwoLabel.appendChild(richLinkBoxTwoB);
        var richLinkBoxTwoInput = document.createElement("input");
        richLinkBoxTwoInput.setAttribute("value", "https://");
        richLinkBoxTwoLabel.appendChild(richLinkBoxTwoInput);
        var richLinkBoxTwoOk = document.createElement("span");
        richLinkBoxTwoOk.innerHTML = "OK";
        richLinkBoxTwo.appendChild(richLinkBoxTwoOk);
        var richLinkBoxTwoClear = document.createElement("span");
        richLinkBoxTwoClear.innerHTML = "Clear";
        richLinkBoxTwo.appendChild(richLinkBoxTwoClear);
        var richLinkFuncShow = function () {
            if (richLinkBoxTwo.style.display == "none") {
                richLinkBoxTwo.style.display = "block";
            } else {
                richLinkBoxTwo.style.display = "none";
            }
        };
        var richLinkFuncOk = function () {
            var inpVal = richLinkBoxTwoInput.value;
            var statCheckLink = _this.checkRegxTextInp("url", richLinkBoxTwoInput.value);
            console.log(statCheckLink);
            if (statCheckLink[0]) {
                _this.Command.execCommand("createLink", false, inpVal);
                var selection = _this.Command.getSelection(); //.focusNode.parentNode;
                var nodeSelection = selection.focusNode;
                console.info("Selection: ");
                console.log(selection);
                console.info("nodeSelection: ");
                console.log(nodeSelection);
                richLinkBoxTwoInput.value = "";
                richLinkBoxTwo.style.display = "none";
                save();
            } else {
                alert("no es un link");
            }
        };
        var richLinkFuncCancel = function () {
            _this.Command.execCommand("unlink", false, "");
            richLinkBoxTwo.style.display = "none";
        };
        richLinkSP.onclick = richLinkFuncShow;
        richLinkBoxTwoOk.onclick = richLinkFuncOk;
        richLinkBoxTwoClear.onclick = richLinkFuncCancel;
        console.groupEnd(); //HeaderSet
    }

    //-----------------------------------------------------------------------------------
    ImagenesSet() {
        var _this = this;
        var richImage_Box = this.nodeImagenes;
        richImage_Box.innerHTML="";
        //Controles Subir Imagen, arrastrar, por url
        var Controls_Div = document.createElement("div");
        Controls_Div.className = "controls";
        richImage_Box.appendChild(Controls_Div);
        var richImageControlUpload = document.createElement("b");
        Controls_Div.appendChild(richImageControlUpload);
        richImageControlUpload.innerHTML = "Subir Imagen";
        var richImageControlUploadInp = document.createElement("input");
        richImageControlUploadInp.type = "file";
        richImageControlUploadInp.setAttribute("multiple", true);
        richImageControlUploadInp.setAttribute("accept", "image/*");
        Controls_Div.appendChild(richImageControlUploadInp);
        var nnReload = document.createElement("button");
        nnReload.innerHTML = "Recargar";
        nnReload.addEventListener("click", function () {
            _this.jxImagenesGet();
        }, false);
        Controls_Div.appendChild(nnReload);
        //---------------------------------
        //Caja contenedora
        var richImageBlock = document.createElement("ul");
        richImageBlock.className = "ImagenesList";
        richImage_Box.appendChild(richImageBlock);
        richImageControlUploadInp.onchange = function (event) {
            var files = event.target.files;
            _this.FileImagenes_Load(files);
            richImageControlUploadInp.value = [];
        };
        //Imagenes que ya han sido subidas y permitidas
        this.ImagenesSetList(richImageBlock);
    }

    ImagenesSetList(nodeUl) {
        var _this = this;
        var arrayImagenes = this.valueImagenes;
        var nnListImages = nodeUl;
        nnListImages.innerHTML = "";
        if (arrayImagenes.length) {
            for (var i = 0; i < arrayImagenes.length; i++) {
                var imageObj = arrayImagenes[i];
                _this.ImagenesSetListItem(nodeUl, imageObj);
            }
        } else {
            var nnLiVacio = document.createElement("li");
            nnLiVacio.innerHTML = "No hay imagenes cargadas";
            nnListImages.appendChild(nnLiVacio);
        }
    }

    ImagenesSetListItem(nodeUl, imageObj) {
        var _this = this;
        var nnListImages = nodeUl;
        var nnLi = document.createElement("li");
        nnListImages.appendChild(nnLi);
        var nnSpanEdit = document.createElement("button");
        nnSpanEdit.innerHTML = "Editar";
        nnLi.appendChild(nnSpanEdit);
        var nnSpanDelete = document.createElement("button");
        nnSpanDelete.innerHTML = "x";
        nnSpanDelete.className = "BotonRojo";
        nnLi.appendChild(nnSpanDelete);
        var nnPrevi = document.createElement("div");
        nnPrevi.className = "preview";
        nnPrevi.setAttribute("data-nkform_imageid", imageObj.id_image);
        nnPrevi.setAttribute("data-src", imageObj.SrcH);
        nnLi.appendChild(nnPrevi);
        var nnImg = document.createElement("img");
        nnImg.src = this.dirRaiz + imageObj.SrcT;
        nnPrevi.appendChild(nnImg);
        var nnBold = document.createElement("b");
        nnBold.innerHTML = imageObj.Caption;
        nnLi.appendChild(nnBold);
        //--------------
        nnPrevi.addEventListener("click", function () {
            _this.ImagenCommand(nnPrevi);
        }, false);
        nnSpanDelete.addEventListener("click", function () {
            _this.jxImagenEliminar(imageObj.id_image, nnLi);
        }, false);
    }

    ImagenCommand(nodeActivate) {
        console.group("Command Image");
        var nnImage = document.createElement("img");
        nnImage.setAttribute("data-nkform-imageid", nodeActivate.dataset.nkform_imageid);
        nnImage.setAttribute("src", nodeActivate.dataset.src);
        console.info(nnImage);
        var selection = this.Command.getSelection(); //.focusNode.parentNode;
        var nodeSelection = selection.focusNode;
        var node = nodeSelection.parentNode;
        var nodeNext = node.nextSibling;
        console.info("Selection: ");
        console.log(selection);
        console.info("nodeSelection: ");
        console.log(nodeSelection);
        console.info("node: ");
        console.log(node);
        console.info("nodeNext: ");
        console.log(nodeNext);
        //----Anexar
        if (nodeSelection.tagName === "BODY" || nodeSelection.tagName === "body") {
            nodeSelection.appendChild(nnImage);
        } else {
            node.parentNode.insertBefore(nnImage, nodeNext);
        }
        //command.execCommand("insertHTML",false,nnImage.outerHTML);
        this.SaveFrame();
        this.nodeFrame.focus();
        console.groupEnd();
    }

    FileImagenes_Load(files) {
        this.FileImagenes_LoadValues=files;
        this.FileImagenes_Listos=[];
        this.FileImagenes_Canvas=[];
        this.FileImagenes_CanvasCargados=0;
        for (var i = 0; i < files.length; i++) {
            this.FileImagen_Load(files[i], i, files.length);
        }
    }

    FileImagen_Load(file, itera, length) {
        //elements
        var _this=this;
        
        let canvas = document.createElement("canvas");
        let ctx = canvas.getContext("2d");
        let url = URL.createObjectURL(file);
        let img = new Image();
        this.FileImagenes_Listos[itera]=img;
        img.onload = function () {         
            var MAX_WIDHT = _this.objOptions.maxWidth || 950;
            var MAX_HEIGHT = _this.objOptions.maxHeight || 950;
            var width = img.width;
            var height = img.height;
            //----------
            var orientation = null;
            if (width >= height) {
                orientation = "horizontal";
            } else if (height > width) {
                orientation = "vertical";
            }
            //----------
            switch (orientation) {
                case "vertical":
                    if (height > MAX_HEIGHT) {
                        width *= MAX_HEIGHT / height;
                        height = MAX_HEIGHT;
                    }
                    break;
                case "horizontal":
                    if (width > MAX_WIDHT) {
                        height *= MAX_WIDHT / width;
                        width = MAX_WIDHT;
                    }
                    break;
                default:
                    width = MAX_WIDHT;
                    height = MAX_HEIGHT;
            }
            canvas.width = width;
            canvas.height = height;
            ctx.drawImage(img, 0, 0, width, height);
            _this.FileImagenes_Canvas[itera]=canvas;
            _this.FileImagenes_CanvasCargados+=1;
            if (_this.FileImagenes_CanvasCargados == length) {
                _this.ImagenesUploadBox();
            }
            globalThis.URL.revokeObjectURL(url);
        }; //Fin onloadimage
        img.src = url;
    } //--Fin Cargar Imagen

    ImagenesUploadBox() {
        "use strict";
        var _this = this;
        //console.warn(this);
        globalThis.scrollTo(0, 0);
        var boxBlack = document.createElement("div");
        boxBlack.classList.add("TextareaRichNK_UpBoxblack");
        var boxContent = document.createElement("div");
        boxContent.classList.add("TextareaRichNK_UpBox");
        var firstChild = document.body.firstChild;
        document.body.insertBefore(boxBlack, firstChild);
        document.body.insertBefore(boxContent, firstChild);
        //---------------------------------------------------
        var h1 = document.createElement("h1");
        h1.innerHTML = "Subir Imagenes";
        boxContent.appendChild(h1);
        var parr = document.createElement("p");
        parr.innerHTML = "Las imagenes seleccionadas solo seran de uso exclusivo del contenido del articulo. Estas seran comprimidas y redimensionadas para su uso.";
        boxContent.appendChild(parr);
        var previewBox = document.createElement("ul");
        previewBox.classList.add("UpList");

        boxContent.appendChild(previewBox);
        //------------------------------------------------------
        
        //------------------------------------------------------
        for (var i = 0; i < this.FileImagenes_Listos.length; i++) {
            var nLi = document.createElement("li");
            previewBox.appendChild(nLi);
            nLi.appendChild(this.FileImagenes_Listos[i]);
            var nInptxt = document.createElement("textarea");
            nLi.appendChild(nInptxt);
            var nBt = document.createElement("button");
            nLi.appendChild(nBt);
            nBt.innerHTML = "Subir Imagen";
            nBt.onclick = _this.jxImagenSubir.bind(this, this.FileImagenes_Canvas[i], nInptxt, nLi);
        }
        //------------------------------------------------------
        var resizeFunction = function () {
            var wVent = globalThis.innerWidth;
            var wB = boxContent.offsetWidth;
            var posX = (wVent / 2) - (wB / 2);
            var hVent = globalThis.innerHeight;
            var posY = (hVent * 5) / 100;
            /*
            var hVent=globalThis.innerHeight;
            var scrollAct=globalThis.pageYOffset;
            var posY=scrollAct+(hVent*5/100);
            */
            boxContent.style.left = posX + "px";
            boxContent.style.top = posY + "px";
        };
        resizeFunction();
        globalThis.addEventListener("resize", resizeFunction, false);
        //-------------------------------------------------------
        boxBlack.onclick = function () {
            document.body.removeChild(boxContent);
            document.body.removeChild(boxBlack);
            globalThis.removeEventListener("resize", resizeFunction, false);
            //globalThis.removeEventListener("scroll",scrollFunction,false);
            _this.jxImagenesGet();
        };
    }

    jxImagenSubir(canvas, text, boxPrev) {
        var _this=this;
        var subirImagenBlob = function (blob) {
            var fd = new FormData();
            fd.append("TextareaRichNK", "ImagenSubir");
            fd.append("KeyJX", _this.KeyJX);
            fd.append("Caption", text.value);
            fd.append("Imagen", blob);
            for(var i=0;i<_this.objOptions.valuesSend.length;i++){
                fd.append(_this.objOptions.valuesSend[i][0], _this.objOptions.valuesSend[i][1]);
            }
            var xhr = new XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (e) {
                if (e.lengthComputable) {
                    var loaded = neoKiri.formatSizeUnits(e.loaded);
                    var total = neoKiri.formatSizeUnits(e.total);
                    var percentage = Math.round((loaded * 100) / total);
                    boxPrev.innerHTML = loaded + "/" + total + "[" + percentage + "%]";
                }
            }, false);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.group("JX: TextareaRichNK::subirImagen()");
                    try {
                        var responseJSON=JSON.parse(xhr.responseText);
                    } catch (error) {
                        console.log(error);
                        console.log(xhr.responseText);
                        return false;
                    }
                    console.info(responseJSON);
                    console.groupEnd();
                    if(responseJSON.RespuestaBool){
                        //_this.jxImagenesGet();
                    }
                    var parent = boxPrev.parentNode;
                    parent.removeChild(boxPrev);
                }
            };
            xhr.open("POST", _this.urlAction, true);
            xhr.send(fd);
        };
        var urlBlob = canvas.toBlob(subirImagenBlob);
    }

    jxImagenEliminar(imageId, nodeLi) {
        var boolConfirm = confirm("Estas seguro que deseas eliminar esta imagen");
        if (!boolConfirm) {
            return false;
        }
        var fd = new FormData();
        fd.append("TextareaRichNK", "ImagenEliminar");
        fd.append("KeyJX", this.KeyJX);
        fd.append("ImagenID", imageId);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
            fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
        }
        //-------
        fetch(this.urlAction, {method: "POST", body: fd})
        .then(resp=>resp.text())
        .then(data=>{
            console.group("JX: TextareaRich.jxImagenEliminar()");
            try {
                data=JSON.parse(data);
            } catch (error) {
                console.log(error);
            }
            console.log(data);
            console.groupEnd();
            if(data.RespuestaBool) {
                this.jxImagenesGet();
            }
        })
        .catch(error=>{
            console.error(error);
        });
    }

    jxImagenesGet() {
        var _this = this;
        var fd = new FormData();
        fd.append("TextareaRichNK", "ImagenesGet");
        fd.append("KeyJX", _this.KeyJX);
        for (var i = 0; i < _this.objOptions.valuesSend.length; i++) {
            fd.append(_this.objOptions.valuesSend[i][0], _this.objOptions.valuesSend[i][1]);
        }
        fetch(this.urlAction, {method: "POST", body: fd})
        .then(resp=>resp.text())
        .then(data=>{
            console.group("JX: TextareaRich.jxImagenesGet()");
            try {
                data=JSON.parse(data);
            } catch (error) {
                console.log(error);
                console.log(data);
                return false;
            }
            console.log(data);
            console.groupEnd();
            if (data.RespuestaBool) {
                this.valueImagenes = data.Imagenes;
                this.ImagenesSet();
                this.ContenidoSet();
            }
        })
        .catch(error=>{
            console.error(error);
        });
    }

    //-----------------------------------------------------------------------------------
    VideosSetBox() {
        var _this = this;
        var videoBox = this.nodeVideos;
        videoBox.innerHTML = "";
        //Controles que cambiaran entre videos de subida, videos de youtbe o facebook entre otros controlados mediante radios
        var nnDivControls = document.createElement("div");
        nnDivControls.className = "VideoControls";
        videoBox.appendChild(nnDivControls);
        //------redioYoutube
        var nnYT_L = document.createElement("label");
        nnDivControls.appendChild(nnYT_L);
        var nnYT_R = document.createElement("input");
        nnYT_R.type = "radio";
        nnYT_R.name = "setVideo_checkBox";
        nnYT_L.appendChild(nnYT_R);
        var nnYT_B = document.createElement("b");
        nnYT_B.innerHTML = "YouTube";
        nnYT_L.appendChild(nnYT_B);
        //------radioVIdeosNormales
        var nnV_L = document.createElement("label");
        nnDivControls.appendChild(nnV_L);
        var nnV_R = document.createElement("input");
        nnV_R.type = "radio";
        nnV_R.name = "setVideo_checkBox";
        nnV_L.appendChild(nnV_R);
        var nnV_B = document.createElement("b");
        nnV_B.innerHTML = "Video";
        nnV_L.appendChild(nnV_B);
        //----------------------------Caja contenedora de la opcion de subida de video
        var nnVideoUploadBox = document.createElement("div");
        nnVideoUploadBox.className = "VideoUploadBox";
        videoBox.appendChild(nnVideoUploadBox);
        var nnDivVideoUpload_BoxYoutube = document.createElement("div");
        nnDivVideoUpload_BoxYoutube.className = "videoBox_Upload_Youtube";
        nnDivVideoUpload_BoxYoutube.style.display = "none";
        nnVideoUploadBox.appendChild(nnDivVideoUpload_BoxYoutube);
        var nnDivVideoUpload_BoxVideo = document.createElement("div");
        nnDivVideoUpload_BoxVideo.className = "videoBox_Upload_Video";
        nnDivVideoUpload_BoxVideo.style.display = "none";
        nnVideoUploadBox.appendChild(nnDivVideoUpload_BoxVideo);
        //--------------------------------------------------Caja contenedora de los videos listos cargados
        var nnDivVideoReady = document.createElement("ul");
        nnDivVideoReady.className = "VideosLista";
        videoBox.appendChild(nnDivVideoReady);
        //-------------------------
        nnYT_R.onclick = function () {
            _this.VideoYoutubeBox(nnVideoUploadBox);
        };
        nnV_R.onclick = function () {
            nnDivVideoUpload_BoxVideo.style.display = "block";
            nnDivVideoUpload_BoxYoutube.style.display = "none";
        };
        var setVideoUpload = function () {
            //TODO:
            nnDivControlsUpload.innerHTML = "";
        };
        //--------------------------------------
        this.VideoYoutubeBox(nnVideoUploadBox);
        this.VideosListaSet(nnDivVideoReady);
    }

    VideoYoutubeBox(nnVideoUploadBox) {
        var _this=this;
        nnVideoUploadBox.innerHTML="";
        var divInputId = document.createElement("div");
        nnVideoUploadBox.appendChild(divInputId);
        var divInputId_Title = document.createElement("b");
        divInputId_Title.innerHTML = "Video ID: ";
        divInputId.appendChild(divInputId_Title);
        var divInputId_Inp = document.createElement("input");
        divInputId.appendChild(divInputId_Inp);
        var spanButtonAddIdYoutube = document.createElement("button");
        spanButtonAddIdYoutube.innerHTML = "Chequear ID";
        spanButtonAddIdYoutube.className = "buttonNormal";
        divInputId.appendChild(spanButtonAddIdYoutube);
        //------------
        var divListVideoYoutubeUpload = document.createElement("ul");
        nnVideoUploadBox.appendChild(divListVideoYoutubeUpload);
        //------------
        spanButtonAddIdYoutube.onclick = function () {
            _this.VideoYoutubeUploadItem(divInputId_Inp.value, divListVideoYoutubeUpload);
        };
    }

    VideosListaSet(nnDivVideoReady) {
        nnDivVideoReady.innerHTML = "";
        for (var i = 0; i < this.valueVideos.length; i++) {
            this.VideoListaSet(this.valueVideos[i], nnDivVideoReady);
        }
    }

    VideoListaSet(objValueVideo, nodeList) {
        var _this=this;
        /*
        Descripcion: null
        Duracion: null
        Src_ID: "QvUEmQzluLM"
        Titulo: "Liquid Stranger - The Molecule Man (Liquid Stranger Remix)"
        Video_Type: "Youtube"
        id_video: 1
        */
        var nnLi = document.createElement("li");
        objValueVideo.nnLi = nnLi;
        nodeList.appendChild(nnLi);
        var nnDivControls = document.createElement("div");
        nnLi.appendChild(nnDivControls);
        var nnButtonAdd = document.createElement("button");
        nnButtonAdd.innerHTML = "+";
        nnDivControls.appendChild(nnButtonAdd);
        var nnButtonDelete = document.createElement("button");
        nnButtonDelete.innerHTML = "X";
        nnButtonDelete.className = "BotonRojo";
        nnDivControls.appendChild(nnButtonDelete);
        //--------------------------------------------
        var nnImg = document.createElement("img");
        nnImg.src = "https://img.youtube.com/vi/" + objValueVideo.Src_ID + "/hqdefault.jpg";
        nnLi.appendChild(nnImg);
        var nnBr = document.createElement("br");
        nnLi.appendChild(nnBr);
        var nnBTitleYoutube = document.createElement("b");
        nnBTitleYoutube.innerHTML = objValueVideo.Titulo;
        nnLi.appendChild(nnBTitleYoutube);
        //----
        nnButtonAdd.onclick = function () {
            _this.VideoCommand(objValueVideo);
        };
        nnButtonDelete.onclick = function () {
            _this.jxVideoEliminar(objValueVideo);
        };
    }

    VideoCommand(objValueVideo) {
        console.group("Command Video");
        var nnVideoBox = document.createElement("div");
        var nnVideo = document.createElement("iframe");
        nnVideo.setAttribute("data-nkform-videoidyt", objValueVideo.VideoID);
        nnVideo.src = 'https://www.youtube.com/embed/' + objValueVideo.Src_ID;
        console.info(nnVideo);
        nnVideoBox.appendChild(nnVideo);
        var selection = this.Command.getSelection(); //.focusNode.parentNode;
        var nodeSelection = selection.focusNode;
        var node = nodeSelection.parentNode;
        var nodeNext = node.nextSibling;
        console.info("Selection: ");
        console.log(selection);
        console.info("nodeSelection: ");
        console.log(nodeSelection);
        console.info("node: ");
        console.log(node);
        console.info("nodeNext: ");
        console.log(nodeNext);
        //----Anexar
        if (nodeSelection.tagName === "BODY" || nodeSelection.tagName === "body") {
            nodeSelection.appendChild(nnVideoBox);
        } else {
            node.parentNode.insertBefore(nnVideoBox, nodeNext);
        }
        //command.execCommand("insertHTML",false,nnImage.outerHTML);
        this.SaveFrame();
        this.nodeFrame.focus();
        console.groupEnd();
    }

    VideoYoutubeUploadItem(id_videoIn, listBox) {
        var _this=this;
        var objItemUploadYoutube = {
            id_video: id_videoIn,
            nodeUl: listBox,
            nodeLi: null,
            nodePreview: null,
            itemYoutubeReady: false,
            itemYoutubeTitle: null,
        };
        var nnLiYoutubeItem = document.createElement("li");
        objItemUploadYoutube.nodeLi = nnLiYoutubeItem;
        listBox.appendChild(nnLiYoutubeItem);
        var nnBTitleYoutube = document.createElement("b");
        nnBTitleYoutube.innerHTML = "Youtube ID: ";
        nnLiYoutubeItem.appendChild(nnBTitleYoutube);
        var nnDivYoutubePreview = document.createElement("div");
        nnLiYoutubeItem.appendChild(nnDivYoutubePreview);
        objItemUploadYoutube.nodePreview = nnDivYoutubePreview;
        var nnDivControlUploadItem = document.createElement("div");
        nnLiYoutubeItem.appendChild(nnDivControlUploadItem);
        var nnButtonAdd = document.createElement("button");
        nnButtonAdd.innerHTML = "Añadir";
        nnDivControlUploadItem.appendChild(nnButtonAdd);
        var nnButtonCancel = document.createElement("button");
        nnButtonCancel.innerHTML = "Cancelar";
        nnButtonCancel.className = "BotonAmarillo";
        nnDivControlUploadItem.appendChild(nnButtonCancel);
        //----------------
        nnButtonAdd.onclick = function () {
            _this.jxVideoYoutubeUpload(objItemUploadYoutube, nnButtonAdd);
        };
        nnButtonCancel.onclick = function () {
            var parentNode = objItemUploadYoutube.nodeLi.parentNode;
            parentNode.removeChild(objItemUploadYoutube.nodeLi);
        };
        this.VideoYoutubeUploadItem_preview(objItemUploadYoutube);
    }

    VideoYoutubeUploadItem_preview(objItemUploadYoutube) {
        var _this=this;
        //FIXME: Mejorar
        var boxGet = neoKiri.BoxDivComplete();
        var box = boxGet[0];
        var boxAction = boxGet[1];
        var nnDiv=document.createElement("div");
        box.appendChild(nnDiv);
        var nnFrameYoutube = document.createElement("iframe");
        nnFrameYoutube.id = "player";
        nnFrameYoutube.type = "text/html";
        nnFrameYoutube.width = "640";
        nnFrameYoutube.height = "360";
        nnFrameYoutube.src = "https://www.youtube.com/embed/" + objItemUploadYoutube.VideoID + "?enablejsapi=1&origin=" + globalThis.location;
        nnFrameYoutube.frameborder = "0";
        nnDiv.appendChild(nnFrameYoutube);
        var nnTexto = document.createElement("div");
        nnDiv.appendChild(nnTexto);
        var nnTituloInp = document.createElement("input");
        nnTexto.appendChild(nnTituloInp);
        var nnDivControls = document.createElement("div");
        nnDiv.appendChild(nnDivControls);
        var nnButtonSubir = document.createElement("button");
        nnButtonSubir.innerHTML = "Agregar Video";
        nnDivControls.appendChild(nnButtonSubir);
        nnButtonSubir.onclick = function () {
            objItemUploadYoutube.itemYoutubeTitle = nnTituloInp.value;
            _this.jxVideoYoutubeUpload(objItemUploadYoutube, nnDiv);
        };
        var nnButtonCancelar = document.createElement("button");
        nnButtonCancelar.innerHTML = "Cancelar";
        nnButtonCancelar.className = "BotonAmarillo";
        nnDivControls.appendChild(nnButtonCancelar);
        nnButtonCancelar.onclick = boxAction;
    }

    jxVideosGet() {
        var _this = this;
        var fd = new FormData();
        fd.append("TextareaRichNK", "VideosGet");
        fd.append("KeyJX", this.KeyJX);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
            fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
        }
        fetch(this.urlAction, {method: "POST", body: fd})
        .then(resp=>resp.text())
        .then(data=>{
            console.group("JX: TextareaRich.jxVideosGet()");
            try {
                data=JSON.parse(data);
            } catch (error) {
                
            }
            console.info(data);
            console.groupEnd();
            if (data.RespuestaBool) {
                this.valueVideos = data.valueVideos;
                this.VideosSetBox();
                this.ContenidoSet();
            }
        })
        .catch(error=>{
            console.error(error);
        });
    }

    jxVideoYoutubeUpload(objItemUploadYoutube, nnDiv) {
        this.disabled=true;
        var buttonText=this.innerHTML;
        this.innerHTML="Subiendo Video..";
        //-----------------
        var _this=this;
        var fd = new FormData();
        fd.append("TextareaRichNK", "VideoYoutubeSubir");
        fd.append("KeyJX", _this.KeyJX);
        fd.append("Titulo", objItemUploadYoutube.itemYoutubeTitle);
        fd.append("VideoTipo", "Youtube");
        fd.append("VideoID", objItemUploadYoutube.id_video);
        for(var i=0;i<_this.objOptions.valuesSend.length;i++){
            fd.append(_this.objOptions.valuesSend[i][0], _this.objOptions.valuesSend[i][1]);
        }
        fetch(this.urlAction, {method:"POST", body: fd})
        .then(resp=>resp.json())
        .then(data=>{
            this.disabled=false;
            this.innerHTML=buttonText;
            //----------
            console.group("TextareaRichNK.jxVideoYoutubeUpload()");
            console.log(data);
            console.groupEnd();
            if(data.RespuestaBool) {
                this.jxVideosGet();
                nnDiv.innerHTML="Video \""+objItemUploadYoutube.itemYoutubeTitle+"\" Subido";
                //nnDiv.parentNode.removeChild(nnDiv);
            }
        });
    }

    jxVideoEliminar(objValueVideo) {
        var boolConfirm = confirm("Estas seguro que deseas eliminar este video");
        if (!boolConfirm) {
            return false;
        }
        console.info("jxVideoEliminar");
        var fd = new FormData();
        fd.append("TextareaRichNK", "VideoEliminar");
        fd.append("KeyJX", this.KeyJX);
        fd.append("VideoID", objValueVideo.id_video);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
            fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
        }
        //-------
        fetch(this.urlAction, {method: "POST", body: fd})
        .then(resp=>resp.text())
        .then(data=>{
            try {
                data=JSON.parse(data);
            } catch (error) {
                
            }
            console.log(data);
            if(data.RespuestaBool) {
                this.jxVideosGet();
            }
        })
        .catch(error=>{
            console.error(error);
        });
    }

    jxContenidoGet() {
        var fd = new FormData();
        fd.append("TextareaRichNK", "ContenidoGet");
        fd.append("KeyJX", this.KeyJX);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
            fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
        }
        fetch(this.urlAction, {method: "POST", body: fd})
        .then(resp=>resp.text())
        .then(data=>{
            console.info("JX: TextareaRich.jxContenidoGet()");
            try {
                data=JSON.parse(data);
            } catch (error) {
                console.warn(data);
            }
            if (data.RespuestaBool) {
                this.valueContenido = data.Contenido || "";
                this.valueImagenes = data.Imagenes || [];
                this.valueVideos = data.Videos || [];
            }
            this.ConfigAll();
            console.groupEnd();
        })
        .catch(error=>{
            console.error(error);
        });
    }

    SaveFrame() {
        console.info("salvando FrameText");
        var txtFrame = this.CommandBody.innerHTML;
        var valueBBCodeResult = neoKiri.html2bbcode(txtFrame, this.valueImagenes);
        if (!valueBBCodeResult) {
            this.CommandBody.innerHTML = "<p>Ingresa el texto...</p>";
            valueBBCodeResult = "[parrafo]Ingresa el texto...[/parrafo]";
        }
        this.valueContenido=valueBBCodeResult;
        this.nodeTextarea.value = valueBBCodeResult;

    }

    SaveTextarea() {
        var txt = this.nodeTextarea.value;
        txt = txt.replace(/\r?\n/g, "<br/>");
        this.valueContenido = txt;
        this.CommandBody.innerHTML = neoKiri.bbcode2html(txt, this.valueImagenes, this.valueVideos, true, "");
    }

    ContenidoSet() {
        this.nodeTextarea.value = this.valueContenido;
        this.SaveTextarea();
    }

    FooterSet() {
        var _this=this;
        this.nodeFooter.innerHTML="";
        var nodeButtonEnviar=document.createElement("button");
        this.nodeFooter.appendChild(nodeButtonEnviar);
        nodeButtonEnviar.innerHTML="Guardar Cambios";
        nodeButtonEnviar.className="ButtonSubmit";
        nodeButtonEnviar.onclick=function() {
            nodeButtonEnviar.disabled=true;
            nodeButtonEnviar.innerHTML="Guardando Cambios por favor Espere";
            var fd = new FormData();
            fd.append("TextareaRichNK", "ContenidoSet");
            fd.append("KeyJX", _this.KeyJX);
            fd.append("Contenido", _this.valueContenido);
            for (var i = 0; i < _this.objOptions.valuesSend.length; i++) {
                fd.append(_this.objOptions.valuesSend[i][0], _this.objOptions.valuesSend[i][1]);
            }
            //-------
            fetch(_this.urlAction, {method: "POST", body: fd})
            .then(resp=>resp.json())
            .then(data=>{
                console.log(data);
                if(data.RespuestaBool) {
                    nodeButtonEnviar.innerHTML="Guardar Cambios";
                    nodeButtonEnviar.disabled=false;
                } else {
                    nodeButtonEnviar.disabled=true;
                }
            })
            .catch(error=>{
                nodeButtonEnviar.disabled=false;
                console.error(error);
            });
        };
    }
}