using UnityEngine;
using UnityEngine.UI;
using System.Collections;

public class InfoCanvas : SingleTon<InfoCanvas> {

    //참조
    [HideInInspector]
    public BusInfo bus_info_data;
    private GameObject weather_info, sensor_info, bus_info, gps_info;
    private GameObject weather_btn, sensor_btn, bus_btn, gps_btn;
    private Image menu_btn;

    //메뉴 버튼 OnOff 여부
    private bool menu_toggle = false;

    //초기화
    void Start()
    {
        bus_info_data = transform.FindChild("BusInfo").GetComponent<BusInfo>();

        weather_info = transform.FindChild("WeatherInfo").gameObject;
        sensor_info = transform.FindChild("SensorInfo").gameObject;
        bus_info = transform.FindChild("BusInfo").gameObject;
        gps_info = transform.FindChild("GPSInfo").gameObject;

        menu_btn = transform.FindChild("Buttons").FindChild("Menu").GetComponent<Image>();
        weather_btn = transform.FindChild("Buttons").FindChild("WeatherBtn").gameObject;
        sensor_btn = transform.FindChild("Buttons").FindChild("SensorBtn").gameObject;
        bus_btn = transform.FindChild("Buttons").FindChild("BusBtn").gameObject;
        gps_btn = transform.FindChild("Buttons").FindChild("GPSBtn").gameObject;
    }

    //메뉴 버튼
    public void MenuBtn()
    {
        if (!menu_toggle)
        {
            menu_btn.color = Color.yellow;
            weather_btn.SetActive(true);
            sensor_btn.SetActive(true);
            bus_btn.SetActive(true);
            gps_btn.SetActive(true);
            menu_toggle = true;
        }
        else
        {
            menu_btn.color = Color.white;
            weather_btn.SetActive(false);
            sensor_btn.SetActive(false);
            bus_btn.SetActive(false);
            gps_btn.SetActive(false);
            menu_toggle = false;
        }
    }

    //날씨 버튼
    public void WeatherBtn()
    {
        if (!weather_info.activeSelf)
            weather_info.SetActive(true);
        else
            weather_info.SetActive(false);
    }

    //센서 버튼
    public void SensorBtn()
    {
        if (!sensor_info.activeSelf)
            sensor_info.SetActive(true);
        else
            sensor_info.SetActive(false);
    }

    //버스 버튼
    public void BusBtn()
    {
        if (!bus_info.activeSelf)
            bus_info.SetActive(true);
        else
            bus_info.SetActive(false);
    }

    //GPS 버튼
    public void GPSBtn()
    {
        if (!gps_info.activeSelf)
            gps_info.SetActive(true);
        else
            gps_info.SetActive(false);
    }
}
