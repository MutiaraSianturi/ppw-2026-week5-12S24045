<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;

class SoapController extends Controller
{
    public function wsdl()
    {
        $wsdlContent = '<?xml version="1.0" encoding="UTF-8"?>
        <definitions name="SipusService"
             targetNamespace="http://localhost:8000/soap/sipus"
             xmlns="http://schemas.xmlsoap.org/wsdl/"
             xmlns:tns="http://localhost:8000/soap/sipus"
             xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/"
             xmlns:xsd="http://www.w3.org/2001/XMLSchema">
             
            <message name="CheckStatusRequest">
                <part name="nim" type="xsd:string"/>
            </message>
            <message name="CheckStatusResponse">
                <part name="is_cleared" type="xsd:boolean"/>
            </message>
            
            <portType name="SipusPortType">
                <operation name="checkStatus">
                    <input message="tns:CheckStatusRequest"/>
                    <output message="tns:CheckStatusResponse"/>
                </operation>
            </portType>
            
            <binding name="SipusBinding" type="tns:SipusPortType">
                <soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"/>
                <operation name="checkStatus">
                    <soap:operation soapAction="http://localhost:8000/soap/sipus/checkStatus"/>
                    <input><soap:body use="encoded" namespace="http://localhost:8000/soap/sipus" encodingStyle="http://schemas.xmlsoap.org/soap/encoding"/></input>
                    <output><soap:body use="encoded" namespace="http://localhost:8000/soap/sipus" encodingStyle="http://schemas.xmlsoap.org/soap/encoding"/></output>
                </operation>
            </binding>
            
            <service name="SipusService">
                <port name="SipusPort" binding="tns:SipusBinding">
                    <soap:address location="http://localhost:8000/soap/sipus"/>
                </port>
            </service>
        </definitions>';

        return response($wsdlContent, 200)->header('Content-Type', 'text/xml');
    }

    public function handle(Request $request)
    {
        $isCleared = true;

        $soapResponse = '<?xml version="1.0" encoding="UTF-8"?>
        <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
            <SOAP-ENV:Body>
                <ns1:checkStatusResponse xmlns:ns1="http://localhost:8000/soap/sipus">
                    <is_cleared xsi:type="xsd:boolean" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' . ($isCleared ? 'true' : 'false') . '</is_cleared>
                </ns1:checkStatusResponse>
            </SOAP-ENV:Body>
        </SOAP-ENV:Envelope>';

        return response($soapResponse, 200)->header('Content-Type', 'text/xml');
    }
}