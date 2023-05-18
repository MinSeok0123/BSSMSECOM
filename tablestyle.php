.modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: 12% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 400px;
            height: 400px;
            border-radius: 20px;
        }

        @media (max-width: 1024px) {
        .modal-content {
            margin: 30vh auto;
        }
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .time {
            position: absolute;
            top:30px;
            left:30px;
        }

        .timetext{
            font-size:25px;
            font-weight: 500;
        }

        .시간{
            font-size:18px;
            font-weight:300;
            color:gray;
        }

        .inputwrap{
            position: relative;
            width:100%;
            height:100px;
        }

        .label{
            left:12px;
            top:-18px;
            position: absolute;
            font-size:13px;
        }

        .clicklabel{
            color:blue;
        }

        .input{
            padding:10px;
            font-size: 20px;
            width:300px;
            height:30px;
            border-radius:15px;
        }

        .form {
            position: absolute;
            top:130px;
            width:100%;
            left:0;
            height:auto;
        }

        .formwrap{
            padding:30px;
            height:130px;
            display:flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .submitwrap{
            left:0;
            position: absolute;
            bottom:20px;
            height:auto;
            width:100%;
            display:flex;
            justify-content: center;
            align-items: center;
        }

        .submit {
            cursor: pointer;
            width:75%;
            height:50px;
            background-color:black;
            border:none;
            color:white;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
        }