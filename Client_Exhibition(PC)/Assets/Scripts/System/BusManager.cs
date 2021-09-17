using UnityEngine;
using System.Collections;

public class BusManager : SingleTon<BusManager> {

    //데이터
    private WebConnect web_connect;

    //버스 정보 선택 여부
    [HideInInspector]
    public bool bus_info_select = false;

    //정보
    [HideInInspector]
    public string station_name = "";
    [HideInInspector]
    public string station_id = "";
    [HideInInspector]
    public string bus_name = "";
    [HideInInspector]
    public string bus_id = "";

    [HideInInspector]
    public string[] predict_time = new string[2];

    //갱신관련
    private Define.Timer renew_timer = new Define.Timer(0, 1.0f);

    //초기화
    void Start () {
        web_connect = WebConnect.instance;
    }

    //버스 정보 갱신
    void Update()
    {
        //버스 정보 데이터가 있으면 표시(1초단위 갱신)
        if (renew_timer.AutoTimer())
        {
            RenewBusInfoData();
        }
    }

    //버스 정보 없애기
    public void DeleteBusInfo()
    {
        bus_name = "";
        bus_id = "";
        station_name = "";
        station_id = "";

        bus_info_select = false;
    }

    //버스 정보 받기
    public void SetBusInfo(string _bus_name, string _bus_id, string _station_name, string _station_id)
    {
        bus_name = _bus_name;
        bus_id = _bus_id;
        station_name = _station_name;
        station_id = _station_id;

        bus_info_select = true;        
    }

    //버스 정보 갱신
    public void RenewBusInfoData()
    {
        //선택한 정보가 있다면
        if (bus_info_select)
        {
            StartCoroutine(web_connect.ConnenctWebPost(WebConnect.BusKey.BusInfo, station_id, bus_id));
            //파싱
            if(web_connect.bus_info_data != null)
            {
                predict_time[0] = web_connect.bus_info_data["predictTime1"].ToString();
                predict_time[1] = web_connect.bus_info_data["predictTime2"].ToString();
                for (int i = 0; i < predict_time.Length; i++)
                {
                    if (predict_time[i] != "JsonData object")
                        predict_time[i] += "분";
                    else
                        predict_time[i] = "정보없음";
                }
            }
        }
    }
}
