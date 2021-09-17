using UnityEngine;
using UnityEngine.UI;
using System.Collections;

public class BusInfo : MonoBehaviour {

    //데이터
    private BusManager bus_manager;
    private WebConnect web_connect;

    //컴포넌트
    private Text bus_now, bus_next;
    private DropdownBtn station_btn, bus_btn, range_btn;
    private DropdownScroll station_scroll, bus_scroll, range_scroll;

    //반경
    private string[] ranges = new string[3] { "0.5", "1.0", "1.5" };

    //갱신관련
    private Define.Timer renew_timer = new Define.Timer(0, 1.0f);

    //드랍다운버튼 관련
    private enum DropdownKind
    {
        Station,
        Bus,
        Range
    }

    //초기화
    void Start()
    {
        //데이터 받기
        bus_manager = BusManager.instance;
        web_connect = WebConnect.instance;
        StartCoroutine(web_connect.ConnenctWebPost(WebConnect.DataKey.Station, ranges[0]));

        //컴포넌트
        bus_now = transform.FindChild("Info").FindChild("BusNow").GetComponent<Text>();
        bus_next = transform.FindChild("Info").FindChild("BusNext").GetComponent<Text>();
        station_btn = transform.FindChild("Info").FindChild("StationDropDown").FindChild("DropDown").GetComponent<DropdownBtn>();
        bus_btn = transform.FindChild("Info").FindChild("BusDropDown").FindChild("DropDown").GetComponent<DropdownBtn>();
        range_btn = transform.FindChild("Info").FindChild("RangeDropDown").FindChild("DropDown").GetComponent<DropdownBtn>();
        station_scroll = transform.FindChild("Info").FindChild("StationDropDown").FindChild("Scroll").GetComponent<DropdownScroll>();
        bus_scroll = transform.FindChild("Info").FindChild("BusDropDown").FindChild("Scroll").GetComponent<DropdownScroll>();
        range_scroll = transform.FindChild("Info").FindChild("RangeDropDown").FindChild("Scroll").GetComponent<DropdownScroll>();

        //드롭다운 초기화
        InitDropDown(DropdownKind.Range);
        InitDropDown(DropdownKind.Station);
        InitDropDown(DropdownKind.Bus);

        //데이터 있을 시 드롭다운 버튼 초기화
        if (bus_manager.bus_info_select)
        {
            station_btn.ChangeBtn(bus_manager.station_name, 0);
            bus_btn.ChangeBtn(bus_manager.bus_name, 0);
        }
    }

    void Update()
    {
        //정류소 데이터 있으면 표시
        if (web_connect.station_data != null)
        {
            RenewDropDownScroll(station_scroll, web_connect.station_data, web_connect.station_data_num);
        }

        //버스 목록 데이터가 있으면 표시
        if (web_connect.bus_list_data != null)
        {
            RenewDropDownScroll(bus_scroll, web_connect.bus_list_data, web_connect.bus_list_num);
        }

        //버스 정보 데이터 표시
        if (renew_timer.AutoTimer())
            RenewTexts();
    }

    //드롭다운 초기화
    private void InitDropDown(DropdownKind _drop_kind)
    {
        switch (_drop_kind)
        {
            case DropdownKind.Range:
                bus_manager.DeleteBusInfo();
                range_btn.ChangeBtn("0.5km", 0);
                range_scroll.gameObject.SetActive(true);
                string[] init_range = new string[ranges.Length];
                for (int i = 0; i < ranges.Length; i++)
                    init_range[i] = ranges[i] + "km";
                range_scroll.ChangeItems(init_range);
                range_scroll.InitScrollBar();
                range_scroll.gameObject.SetActive(false);
                break;

            case DropdownKind.Station:
                bus_manager.DeleteBusInfo();
                station_btn.ChangeBtn("정류소 선택", 0);
                station_scroll.gameObject.SetActive(true);
                string[] init_station = new string[1] { "정류소 없음" };
                station_scroll.ChangeItems(init_station);
                station_scroll.InitScrollBar();
                station_scroll.gameObject.SetActive(false);
                break;

            case DropdownKind.Bus:
                bus_btn.ChangeBtn("버스 선택", 0);
                bus_scroll.gameObject.SetActive(true);
                string[] init_bus = new string[1] { "버스 없음" };
                bus_scroll.ChangeItems(init_bus);
                bus_scroll.InitScrollBar();
                bus_scroll.gameObject.SetActive(false);
                break;
        }
    }

    //스크롤 갱신
    private void RenewDropDownScroll(DropdownScroll _scroll, string[,] _string, int _num)
    {
        if(_string != null)
        {
            string[] names = new string[_num];
            for (int i = 0; i < names.Length; i++)
                names[i] = _string[i, 1];
            _scroll.ChangeItems(names);
        }
    }

    //텍스트 갱신
    private void RenewTexts()
    {
        if(web_connect.bus_info_data != null)
        {
            bus_now.text = bus_manager.predict_time[0];
            bus_next.text = bus_manager.predict_time[1];
        }
        else
        {
            bus_now.text = "정류소와 버스를 선택하세요";
            bus_next.text = "정류소와 버스를 선택하세요";
        }
    }

    //정류소 아이템 버튼
    public void StationItemBtn()
    {
        //데이터 초기화
        web_connect.DeleteData(WebConnect.BusKey.BusList);
        web_connect.DeleteData(WebConnect.BusKey.BusInfo);
        InitDropDown(DropdownKind.Bus);

        //버스 리스트 데이터 받기
        if (web_connect.station_data != null)
        {
            string station_id = web_connect.station_data[station_btn.index, 0];
            StartCoroutine(web_connect.ConnenctWebPost(WebConnect.BusKey.BusList, station_id, ""));
        }
    }

    //버스 아이템 버튼
    public void BusItemBtn()
    {
        //데이터 제거
        web_connect.DeleteData(WebConnect.BusKey.BusInfo);

        //버스 정보 갱신
        RenewBusManagerData();
    }

    //반경 아이템 버튼
    public void RangeItemBtn()
    {
        //데이터 초기화
        web_connect.DeleteData(WebConnect.DataKey.Station);
        web_connect.DeleteData(WebConnect.BusKey.BusList);
        web_connect.DeleteData(WebConnect.BusKey.BusInfo);
        InitDropDown(DropdownKind.Station);
        InitDropDown(DropdownKind.Bus);

        //정류소 정보 받기
        StartCoroutine(web_connect.ConnenctWebPost(WebConnect.DataKey.Station, ranges[range_btn.index]));
    }
    
    //버스 매니져 정보 갱신
    private void RenewBusManagerData()
    {
        if (web_connect.bus_list_data != null && bus_btn.GetText() != "버스 선택")
        {
            string station_id = web_connect.station_data[station_btn.index, 0];
            string station_name = web_connect.station_data[station_btn.index, 1];
            string bus_id = web_connect.bus_list_data[bus_btn.index, 0];
            string bus_name = web_connect.bus_list_data[bus_btn.index, 1];

            //정보 설정
            bus_manager.SetBusInfo(bus_name, bus_id, station_name, station_id);

            //정보 갱신
            bus_manager.RenewBusInfoData();
        }
    }

    //스스로 꺼지기
    public void DisableMe()
    {
        if (station_scroll.gameObject.activeSelf)
            station_scroll.gameObject.SetActive(false);
        if (bus_scroll.gameObject.activeSelf)
            bus_scroll.gameObject.SetActive(false);
        if (range_scroll.gameObject.activeSelf)
            range_scroll.gameObject.SetActive(false);

        gameObject.SetActive(false);
    }
}
