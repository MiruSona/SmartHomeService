using UnityEngine;
using System.Collections;

public static class Define {

    //타이머
    public struct Timer
    {
        public float limit;    //한계치
        public float time;     //현재시간

        public Timer(float _time, float _limit)
        {
            time = _time;
            limit = _limit;
        }

        public bool CheckTimer()
        {
            bool send_bool = false;
            if (time < limit)
                time += Time.fixedDeltaTime;
            else
            {
                send_bool = true;               
            }

            return send_bool;
        }

        public bool AutoTimer()
        {
            bool send_bool = false;
            if (time < limit)
                time += Time.fixedDeltaTime;
            else
            {
                send_bool = true;
                time = 0;
            }

            return send_bool;
        }
    }
}
