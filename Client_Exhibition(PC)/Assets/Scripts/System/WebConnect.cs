using UnityEngine;
using System.Collections;
using System.Collections.Generic;
using LitJson;

public class WebConnect : SingleTon<WebConnect> {

    //참조
    private GPSManager gps_manager;

    //URL
    private string sensor_url = "192.168.1.104/mobile/doGetSensorData";
    private string weather_url = "192.168.1.104/mobile/doGetWeatherInfo";
    private string station_url = "192.168.1.104/mobile/doGetBusStationList";
    private string bus_list_url = "192.168.1.104/mobile/doGetBusList";
    private string bus_info_url = "192.168.1.104/mobile/doGetBusInfo";
    //private string sensor_url = "http://roptop.iptime.org/mobile/doGetSensorData";
    //private string weather_url = "http://roptop.iptime.org/mobile/doGetWeatherInfo";
    //private string station_url = "http://roptop.iptime.org/mobile/doGetBusStationList";
    //private string bus_list_url = "http://roptop.iptime.org/mobile/doGetBusList";
    //private string bus_info_url = "http://roptop.iptime.org/mobile/doGetBusInfo";

    //센서 데이터
    [HideInInspector]
    public Dictionary<SensorKey, float> sensor_datas = new Dictionary<SensorKey, float>();
    //날씨 데이터
    [HideInInspector]
    public JsonData weather_data = null;
    [HideInInspector]
    public string tomorrow_index = "1";
    //정류소 데이터
    public readonly int station_data_kind = 2;
    [HideInInspector]
    public int station_data_num = 0;
    [HideInInspector]
    public string[,] station_data = null;   //[인덱스, 0 = ID / 1 = 이름]

    //버스 리스트 데이터
    public readonly int bus_list_kind = 2;
    [HideInInspector]
    public int bus_list_num = 0;
    [HideInInspector]
    public string[,] bus_list_data = null;  //[인덱스, 0 = ID / 1 = 이름]

    //버스 데이터
    [HideInInspector]
    public JsonData bus_info_data = null;

    //센서 키
    public enum SensorKey
    {
        Dust,
        Temperature,
        Humidity,
        Gas
    }

    //데이터 키
    public enum DataKey
    {
        Weather,
        Station
    }

    //버스 키
    public enum BusKey
    {
        BusList,
        BusInfo
    }

    //초기화
    void Awake()
    {
        //GPS매니져
        gps_manager = GPSManager.instance;

        //센서 초기화
        StartCoroutine(ConnenctWebPost(SensorKey.Dust));
        StartCoroutine(ConnenctWebPost(SensorKey.Temperature));
        StartCoroutine(ConnenctWebPost(SensorKey.Humidity));
        StartCoroutine(ConnenctWebPost(SensorKey.Gas));
    }
    
    //센서 리퀘스트(Post)
    public IEnumerator ConnenctWebPost(SensorKey _sensor_key)
    {
        //post 보낼 값
        WWWForm form = new WWWForm();
        //데이터 키에따라 보내기
        WWW postRequest = null;
        switch (_sensor_key)
        {
            case SensorKey.Dust:
                form.AddField("type", "dust");
                postRequest = new WWW(sensor_url, form);
                break;
            case SensorKey.Temperature:
                form.AddField("type", "temperature");
                postRequest = new WWW(sensor_url, form);
                break;
            case SensorKey.Humidity:
                form.AddField("type", "humidity");
                postRequest = new WWW(sensor_url, form);
                break;
            case SensorKey.Gas:
                form.AddField("type", "gas");
                postRequest = new WWW(sensor_url, form);
                break;
        }

        //보냄
        yield return postRequest;

        //json 파싱 - 가장 최근데이터만 받음
        JsonData data = JsonMapper.ToObject(postRequest.text);
        //리스트 있을때만 받음
        if (data.Keys.Contains("info"))
        {
            data = data["info"];
            //데이터 넣기
            switch (_sensor_key)
            {
                case SensorKey.Dust:
                    if (sensor_datas.ContainsKey(SensorKey.Dust))
                        sensor_datas[SensorKey.Dust] = float.Parse(data["sensorVal1"].ToString());
                    else
                        sensor_datas.Add(SensorKey.Dust, float.Parse(data["sensorVal1"].ToString()));
                    break;
                case SensorKey.Temperature:
                    if (sensor_datas.ContainsKey(SensorKey.Temperature))
                        sensor_datas[SensorKey.Temperature] = float.Parse(data["sensorVal1"].ToString());
                    else
                        sensor_datas.Add(SensorKey.Temperature, float.Parse(data["sensorVal1"].ToString()));
                    break;
                case SensorKey.Humidity:
                    if (sensor_datas.ContainsKey(SensorKey.Humidity))
                        sensor_datas[SensorKey.Humidity] = float.Parse(data["sensorVal1"].ToString());
                    else
                        sensor_datas.Add(SensorKey.Humidity, float.Parse(data["sensorVal1"].ToString()));
                    break;
                case SensorKey.Gas:
                    if (sensor_datas.ContainsKey(SensorKey.Gas))
                        sensor_datas[SensorKey.Gas] = float.Parse(data["sensorVal1"].ToString());
                    else
                        sensor_datas.Add(SensorKey.Gas, float.Parse(data["sensorVal1"].ToString()));
                    break;
            }
        }
    }

    //날씨/정류소 리퀘스트(Post)
    public IEnumerator ConnenctWebPost(DataKey _data_key, string _range)
    {
        //GPS 정보 있나 확인(없으면 받기)
        if (gps_manager.latitude == 0)
            yield return StartCoroutine(GPSManager.instance.GetGPS());

        //GPS 에러 확인(에러 있으면 더이상 진행X)
        if (gps_manager.latitude == 0)
        {
            GPSManager.GPSState state = gps_manager.GetGPSState();
            if (state != GPSManager.GPSState.Done && state != GPSManager.GPSState.Connecting)
            {
                //상태창 표시
                gps_manager.ShowGPSState();
                yield break;
            }
        }

        //post 보낼 값
        WWWForm form = new WWWForm();
        //데이터 키에따라 보내기
        WWW postRequest = null;
        switch (_data_key)
        {
            case DataKey.Weather:
                form.AddField("x", gps_manager.longitude.ToString());
                form.AddField("y", gps_manager.latitude.ToString());
                postRequest = new WWW(weather_url, form);
                break;
            case DataKey.Station:
                form.AddField("x", gps_manager.longitude.ToString());
                form.AddField("y", gps_manager.latitude.ToString());
                form.AddField("rad", _range);
                postRequest = new WWW(station_url, form);
                break;
        }

        //보냄
        yield return postRequest;

        //json 파싱
        JsonData data = JsonMapper.ToObject(postRequest.text);

        //데이터 있는 경우에만 진행
        if (data.Keys.Contains("list"))
            data = data["list"];
        else
            yield break;

        switch (_data_key)
        {
            case DataKey.Weather:
                //데이터 받기
                weather_data = data;
                //내일 데이터 인덱스 구하기
                for (int i = 0; i < weather_data.Count; i++)
                {
                    if (weather_data[i.ToString()]["day"].ToString() == "내일")
                    {
                        tomorrow_index = i.ToString();
                        break;
                    }
                }
                break;
            case DataKey.Station:
                //string 초기화
                station_data_num = data.Count;
                station_data = new string[station_data_num, station_data_kind];

                //데이터 받기
                for (int i = 0; i < station_data_num; i++)
                {
                    station_data[i, 0] = data[i]["STATION_ID"].ToString();
                    station_data[i, 1] = data[i]["STATION_NM"].ToString();
                }
                break;
        }
    }

    //버스 정보 리퀘스트(Post)
    public IEnumerator ConnenctWebPost(BusKey _bus_key, string _station_id, string _route_id)
    {
        //post 보낼 값
        WWWForm form = new WWWForm();
        //데이터 키에따라 보내기
        WWW postRequest = null;
        switch (_bus_key)
        {
            case BusKey.BusList:
                form.AddField("stationId", _station_id);
                postRequest = new WWW(bus_list_url, form);
                break;
            case BusKey.BusInfo:
                form.AddField("stationId", _station_id);
                form.AddField("routeId", _route_id);
                postRequest = new WWW(bus_info_url, form);
                break;
        }

        //보냄
        yield return postRequest;

        //json 파싱
        JsonData data = JsonMapper.ToObject(postRequest.text);

        //데이터 있는 경우에만 진행
        if (_bus_key != BusKey.BusInfo)
        {
            if (data.Keys.Contains("list"))
                data = data["list"];
            else
                yield break;
        }
        else
        {
            if (data.Keys.Contains("info"))
                data = data["info"];
            else
                yield break;
        }

        switch (_bus_key)
        {
            case BusKey.BusList:
                //string 초기화
                bus_list_num = data.Count;
                bus_list_data = new string[bus_list_num, bus_list_kind];
                //데이터 받기
                for (int i = 0; i < bus_list_num; i++)
                {
                    bus_list_data[i, 0] = data[i]["routeId"].ToString();
                    bus_list_data[i, 1] = data[i]["routeNM"].ToString();
                }
                break;
            case BusKey.BusInfo:
                bus_info_data = data;
                break;
        }
    }

    //데이터 제거
    public void DeleteData(DataKey _data_key)
    {
        switch (_data_key)
        {
            case DataKey.Station:
                station_data = null;
                break;
            case DataKey.Weather:
                weather_data = null;
                break;
        }
    }

    public void DeleteData(BusKey _bus_key)
    {
        switch (_bus_key)
        {
            case BusKey.BusList:
                bus_list_data = null;
                break;
            case BusKey.BusInfo:
                bus_info_data = null;
                break;
        }
    }

    //웹에 연결
    public IEnumerator ConnenctWeb(string _url)
    {
        //요청
        WWW postRequest = new WWW(_url);
        yield return postRequest;
    }
}
