package org.aspen_discovery.reindexer;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Objects;

public class RecordInfo {
	private String source;
	private String subSource;
	private String recordIdentifier;

	//Formats exist at both the item and record level because
	//Various systems define them in both ways.
	private HashSet<String> formats = new HashSet<>();
	private HashSet<String> formatCategories = new HashSet<>();
	private long formatBoost = 1;

	private String edition;
	private String primaryLanguage;
	private String publisher;
	private String publicationDate;
	private String placeOfPublication;
	private String physicalDescription;
	private boolean isClosedCaptioned;

	private boolean hasParentRecord;
	private boolean hasChildRecord;

	private final ArrayList<ItemInfo> relatedItems = new ArrayList<>();

	public RecordInfo(String source, String recordIdentifier){
		this.source = source;
		this.recordIdentifier = recordIdentifier;
	}

	public String getSource(){
		return this.source;
	}

	void setSubSource(String subSource) {
		this.subSource = subSource;
	}

	public String getSubSource(){
		return this.subSource;
	}

	public long getFormatBoost() {
		return formatBoost;
	}

	public void setFormatBoost(long formatBoost) {
		if (formatBoost > this.formatBoost) {
			this.formatBoost = formatBoost;
		}
	}

	void setEdition(String edition) {
		this.edition = edition;
	}

	public String getEdition(){
		return edition;
	}

	void setPrimaryLanguage(String primaryLanguage) {
		this.primaryLanguage = primaryLanguage;
	}

	String getPrimaryLanguage(){
		return primaryLanguage;
	}

	void setPublisher(String publisher) {
		this.publisher = publisher;
	}

	public String getPublisher() {
		return this.publisher;
	}

	void setPlaceOfPublication(String placeOfPublication) {
		this.placeOfPublication = placeOfPublication;
	}

	public String getPlaceOfPublication() {
		return this.placeOfPublication;
	}

	void setPublicationDate(String publicationDate) {
		this.publicationDate = publicationDate;
	}

	public String getPublicationDate() {
		return this.publicationDate;
	}

	void setPhysicalDescription(String physicalDescription) {
		this.physicalDescription = physicalDescription;
	}

	ArrayList<ItemInfo> getRelatedItems() {
		return relatedItems;
	}

	void setRecordIdentifier(String source, String recordIdentifier) {
		this.source = source;
		this.recordIdentifier = recordIdentifier;
	}

	public String getRecordIdentifier() {
		return recordIdentifier;
	}

	private String recordDetails = null;
	String getDetails() {
		if (recordDetails == null) {
			//None of this changes by scope, so we can just form it once and then return the previous value
			recordDetails = this.getFullIdentifier() + "|" +
					getPrimaryFormat() + "|" +
					getPrimaryFormatCategory() + "|" +
					Util.getCleanDetailValue(edition) + "|" +
					Util.getCleanDetailValue(primaryLanguage) + "|" +
					Util.getCleanDetailValue(publisher) + "|" +
					Util.getCleanDetailValue(placeOfPublication) + "|" +
					Util.getCleanDetailValue(publicationDate) + "|" +
					Util.getCleanDetailValue(physicalDescription)
					;
		}
		return recordDetails;
	}

	private String primaryFormat = null;
	private String primaryFormatCategory = null;
	String getPrimaryFormat() {
		if (primaryFormat == null){
			HashMap<String, Integer> relatedFormats = new HashMap<>();
			HashMap<String, String> formatToFormatCategory = new HashMap<>();
			for (ItemInfo curItem : relatedItems){
				if (curItem.getFormat() != null && !curItem.getFormat().isEmpty()) {
					if (relatedFormats.containsKey(curItem.getFormat())){
						relatedFormats.merge(curItem.getFormat(), 1, Integer::sum);
					}else{
						relatedFormats.put(curItem.getFormat(), relatedFormats.getOrDefault(curItem.getFormat(), 1));
					}
					formatToFormatCategory.put(curItem.getFormat(), curItem.getFormatCategory());
				}
			}

			HashMap.Entry<String, Integer> FormatCounter = null; //need to sort through both string and integer to compare things properly
			String mostUsedFormat = null; //Set most used format to null before iterating through the hashmap

			//for each entry set in relatedFormats
			for (HashMap.Entry<String, Integer> curItem : relatedFormats.entrySet())
			{
				//if current item format count is greater than FormatCounter, set this as mostUsedFormat
				if (FormatCounter == null || curItem.getValue().compareTo(FormatCounter.getValue()) > 0)
				{
					FormatCounter = curItem;
					mostUsedFormat = curItem.getKey(); //get and set the most used format from entrySet with getKey()
				}
			}

			if (mostUsedFormat == null){
				//If we have formats for the record, use that. We only get here if we have no item formats.
				if (!formats.isEmpty()) {
					primaryFormat = formats.iterator().next();
				}

				//This might not be correct if we have multiple formats since the format category could be different
				//for each.
				if (!formatCategories.isEmpty()){
					primaryFormatCategory = formatCategories.iterator().next();
				}
			}else{
				primaryFormat = mostUsedFormat;
				primaryFormatCategory = formatToFormatCategory.get(mostUsedFormat);
			}
		}

		return primaryFormat;
	}

	public String getPrimaryFormatCategory() {
		if (primaryFormatCategory == null) {
			HashMap<String, Integer> relatedFormats = new HashMap<>();
			for (String format : formatCategories) {
				relatedFormats.put(format, 1);
			}
			for (ItemInfo curItem : relatedItems) {
				if (curItem.getFormatCategory() != null) {
					relatedFormats.put(curItem.getFormatCategory(), relatedFormats.getOrDefault(curItem.getFormatCategory(), 1));
				}
			}
			int timesUsed = 0;
			String mostUsedFormat = null;
			for (String curFormat : relatedFormats.keySet()) {
				if (relatedFormats.get(curFormat) > timesUsed) {
					mostUsedFormat = curFormat;
					timesUsed = relatedFormats.get(curFormat);
				}
			}
			primaryFormatCategory = Objects.requireNonNullElse(mostUsedFormat, "Unknown");
		}
		return primaryFormatCategory;
	}

	public void addItem(ItemInfo itemInfo) {
		relatedItems.add(itemInfo);
		itemInfo.setRecordInfo(this);
	}

	HashSet<String> getFormats() {
		return formats;
	}

	String getFirstFormat() {
		if (!formats.isEmpty()) {
			for (String format : formats) {
				return format;
			}
		}
		return null;
	}

	HashSet<String> getFormatCategories() {
		return formatCategories;
	}

	String getFirstFormatCategory() {
		if (!formatCategories.isEmpty()) {
			for (String formatCategory : formatCategories) {
				return formatCategory;
			}
		}
		return null;
	}

	int getNumCopiesOnOrder() {
		int numOrders = 0;
		for (ItemInfo curItem : relatedItems){
			if (curItem.isOrderItem()){
				numOrders += curItem.getNumCopies();
			}
		}
		return numOrders;
	}

	String getFullIdentifier() {
		String fullIdentifier;
		if (subSource != null && !subSource.isEmpty()){
			fullIdentifier = source + ":" + subSource + ":" + recordIdentifier;
		}else{
			fullIdentifier = source + ":" + recordIdentifier;
		}
		return fullIdentifier;
	}

	int getNumPrintCopies() {
		int numPrintCopies = 0;
		for (ItemInfo curItem : relatedItems){
			if (!curItem.isOrderItem() && !curItem.isEContent()){
				numPrintCopies += curItem.getNumCopies();
			}
		}
		return numPrintCopies;
	}

	int getNumEContentCopies() {
		int numEContentCopies = 0;
		for (ItemInfo curItem : relatedItems){
			if (curItem.isEContent()){
				numEContentCopies += curItem.getNumCopies();
			}
		}
		return numEContentCopies;
	}

	public int getNumVirtualItems() {
		int numVirtualItems = 0;
		for (ItemInfo curItem : relatedItems){
			if (curItem.isVirtual()){
				numVirtualItems++;
			}
		}
		return numVirtualItems;
	}

	HashSet<String> getAllEContentSources() {
		HashSet<String> values = new HashSet<>();
		for (ItemInfo curItem : relatedItems){
			values.add(curItem.geteContentSource());
		}
		return values;
	}

	HashSet<String> getAllCallNumbers(){
		HashSet<String> values = new HashSet<>();
		for (ItemInfo curItem : relatedItems){
			values.add(curItem.getCallNumber());
		}
		return values;
	}

	void addFormats(HashSet<String> translatedFormats) {
		this.formats.addAll(translatedFormats);
	}

	void addFormat(String translatedFormat){
		this.formats.add(translatedFormat);
	}

	void addFormatCategories(HashSet<String> translatedFormatCategories) {
		this.formatCategories.addAll(translatedFormatCategories);
	}

	void addFormatCategory(String translatedFormatCategory){
		this.formatCategories.add(translatedFormatCategory);
	}

	boolean hasItemFormats() {
		for (ItemInfo curItem : relatedItems){
			if (curItem.getFormat() != null){
				return true;
			}
		}
		return false;
	}

	void getAutoReindexTimes(HashSet<Long> autoReindexTimes) {
		for (ItemInfo curItem : relatedItems){
			if (curItem.getAutoReindexTime() != null){
				autoReindexTimes.add(curItem.getAutoReindexTime());
			}
		}
	}

	void copyFrom(RecordInfo recordInfo){
		//noinspection unchecked
		this.formats = (HashSet<String>) recordInfo.formats.clone();
		//noinspection unchecked
		this.formatCategories = (HashSet<String>)recordInfo.formatCategories.clone();
		this.formatBoost = recordInfo.formatBoost;
		this.edition = recordInfo.edition;
		this.primaryLanguage = recordInfo.primaryLanguage;
		this.publisher = recordInfo.publisher;
		this.placeOfPublication = recordInfo.placeOfPublication;
		this.publicationDate = recordInfo.publicationDate;
		this.physicalDescription = recordInfo.physicalDescription;
		this.isClosedCaptioned = recordInfo.isClosedCaptioned;
		for (ItemInfo itemInfo : recordInfo.relatedItems) {
			ItemInfo clonedItem = new ItemInfo();
			addItem(clonedItem);
			clonedItem.copyFrom(itemInfo);
		}
	}

	public String getPhysicalDescription() {
		return physicalDescription;
	}

	public boolean isClosedCaptioned() {
		return isClosedCaptioned;
	}

	public void setClosedCaptioned(boolean closedCaptioned) {
		isClosedCaptioned = closedCaptioned;
	}

	public boolean allItemsHaveFormats() {
		for (ItemInfo curItem : relatedItems){
			if (curItem.getFormat() == null){
				return false;
			}
		}
		return true;
	}

	public HashSet<String> getUniqueItemFormats() {
		HashSet<String> uniqueItemFormats = new HashSet<>();
		for (ItemInfo curItem : relatedItems){
			if (curItem.getFormat() != null){
				uniqueItemFormats.add(curItem.getFormat());
			}
		}
		return uniqueItemFormats;
	}

	public HashSet<String> getUniqueItemFormatsCategories() {
		HashSet<String> uniqueItemFormatCategories = new HashSet<>();
		for (ItemInfo curItem : relatedItems){
			if (curItem.getFormat() != null){
				uniqueItemFormatCategories.add(curItem.getFormatCategory());
			}
		}
		return uniqueItemFormatCategories;
	}

	public boolean hasParentRecord() {
		return hasParentRecord;
	}

	public void setHasParentRecord(boolean hasParentRecord) {
		this.hasParentRecord = hasParentRecord;
	}

	public boolean hasChildRecord() {
		return hasChildRecord;
	}

	public void setHasChildRecord(boolean hasChildRecord) {
		this.hasChildRecord = hasChildRecord;
	}

}