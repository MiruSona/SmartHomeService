using UnityEngine;
using UnityEngine.UI;
using System.Collections;
using Vuforia;

public class SystemManager : SingleTon<SystemManager> {

    public class FileManager
    {
        //GPS 정보
        public float latitude = 0f;
        public float longitude = 0f;

        //버스 정보 선택 여부
        public bool bus_select = false;
        public string station_name = "";
        public string station_id = "";
        public string bus_name = "";
        public string bus_id = "";

        public enum Key
        {
            GPS,
            BusData
        }

        public enum DataKey
        {
            //GPS
            Latitude,
            Longitude,
            //Bus
            StationName,
            StationID,
            BusName,
            BusID,
            BusSelect
        }

        //세이브
        public void SaveFile(Key _key)
        {
            switch (_key)
            {
                case Key.GPS:
                    latitude = GPSManager.instance.latitude;
                    longitude = GPSManager.instance.longitude;
                    PlayerPrefs.SetString(DataKey.Latitude.ToString(), latitude.ToString());
                    PlayerPrefs.SetString(DataKey.Longitude.ToString(), longitude.ToString());
                    break;

                case Key.BusData:
                    station_name = BusManager.instance.station_name;
                    station_id = BusManager.instance.station_id;
                    bus_name = BusManager.instance.bus_name;
                    bus_id = BusManager.instance.bus_id;
                    bus_select = BusManager.instance.bus_info_select;
                    PlayerPrefs.SetString(DataKey.StationName.ToString(), station_name);
                    PlayerPrefs.SetString(DataKey.StationID.ToString(), station_id);
                    PlayerPrefs.SetString(DataKey.BusName.ToString(), bus_name);
                    PlayerPrefs.SetString(DataKey.BusID.ToString(), bus_id);
                    PlayerPrefs.SetString(DataKey.BusSelect.ToString(), bus_select.ToString());
                    break;
            }
        }

        //로드
        public void LoadFile(DataKey _data_key)
        {
            string data = "";
            data = PlayerPrefs.GetString(_data_key.ToString());

            if (data != "")
            {
                switch (_data_key)
                {
                    //GPS
                    case DataKey.Latitude: latitude = float.Parse(data); break;
                    case DataKey.Longitude: longitude = float.Parse(data); break;
                    //Bus
                    case DataKey.StationName: station_name = data; break;
                    case DataKey.StationID: station_id = data; break;
                    case DataKey.BusName: bus_name = data; break;
                    case DataKey.BusID: bus_id = data; break;
                    case DataKey.BusSelect: bus_select = bool.Parse(data); break;
                }
            }
        }

        //모든 데이터 저장
        public void SaveAllData()
        {
            SaveFile(Key.GPS);
            SaveFile(Key.BusData);
        }

        //모든 데이터 로드
        public void LoadAllData()
        {
            LoadFile(DataKey.Latitude);
            LoadFile(DataKey.Longitude);
            LoadFile(DataKey.StationName);
            LoadFile(DataKey.StationID);
            LoadFile(DataKey.BusName);
            LoadFile(DataKey.BusID);
            LoadFile(DataKey.BusSelect);

            if(latitude != 0f)
            {
                GPSManager.instance.latitude = latitude;
                GPSManager.instance.longitude = longitude;
            }

            if (bus_select)
            {
                BusManager.instance.station_name = station_name;
                BusManager.instance.station_id = station_id;
                BusManager.instance.bus_name = bus_name;
                BusManager.instance.bus_id = bus_id;
                BusManager.instance.bus_info_select = bus_select;
            }
        }
    }
    public FileManager file_manager = new FileManager();

    //참조
    public GameObject exit_warning;

    void Awake()
    {
        //데이터 불러오기
        file_manager.LoadAllData();
    }

    void Start()
    {
        //카메라 설정
        VuforiaAbstractBehaviour vuforia = FindObjectOfType<VuforiaAbstractBehaviour>();
        vuforia.RegisterVuforiaStartedCallback(OnVuforiaStarted);
        vuforia.RegisterOnPauseCallback(OnPaused);
    }

    void Update()
    {
        //뒤로가기 누르면 종료
        if (Input.GetKeyDown(KeyCode.Escape))
        {
            //데이터 저장
            file_manager.SaveAllData();
            //경고창 켜져있다면 종료
            if (exit_warning.activeSelf)
                Application.Quit();
            //아니면 경고창 켜기
            else
                exit_warning.SetActive(true);
        }
    }

    //카메라 오토로 키기
    private void OnVuforiaStarted()
    {
        CameraDevice.Instance.SetFocusMode(CameraDevice.FocusMode.FOCUS_MODE_CONTINUOUSAUTO);
    }

    private void OnPaused(bool paused)
    {
        if (!paused) // resumed
        {
            // Set again autofocus mode when app is resumed
            CameraDevice.Instance.SetFocusMode(CameraDevice.FocusMode.FOCUS_MODE_CONTINUOUSAUTO);
        }
    }

    //경고창 끄기
    public void ExitWarningBtn()
    {
        exit_warning.SetActive(false);
    }

}
