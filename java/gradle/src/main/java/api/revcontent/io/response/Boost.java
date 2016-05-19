package api.revcontent.io.response;

public class Boost {
    public String id,
            name,
            start_date,
            end_date,
            targeting_type,
            enabled,
            status,
            min_bid,
            max_bid,
            budget,
            cost,
            ctr;

    /**
     *
     * @return String
     */
    public String toString() {
        return
                "\n Name : " + name +
                "\n Start date:" + start_date +
                "\n End date: " + end_date +
                "\n Targeting type: " + targeting_type +
                "\n Enabled: " + enabled +
                "\n Status: " + status +
                "\n Min. Bid: " + min_bid +
                "\n Max. Bid: " + max_bid +
                "\n Budget: " + budget +
                "\n Cost: " + cost +
                "\n Ctr: " + ctr;
    }
}
